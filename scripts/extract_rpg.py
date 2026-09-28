#!/usr/bin/env python3
"""Read-only LCF extraction. Python standard library; see docs/rpg-extraction.md."""
import argparse
import base64
import collections
import csv
import hashlib
import json
import os
import re
from pathlib import Path
import struct

ROOT = Path(__file__).resolve().parents[1]
SCHEMA = ROOT / 'scripts/vendor/liblcf'
REVISION = '6854310c3432e553fd4ae672ce861899c80c3bd0'


def rows(name):
    with (SCHEMA / name).open(newline='') as f:
        return list(csv.DictReader(f))


class Reader:
    def __init__(self, data):
        self.data, self.pos = data, 0

    def take(self, n):
        if n < 0 or self.pos + n > len(self.data):
            raise ValueError(f'Truncated input at {self.pos}, requested {n}')
        result = self.data[self.pos:self.pos+n]
        self.pos += n
        return result

    def integer(self):
        value = 0
        for _ in range(5):
            byte = self.take(1)[0]
            value = (value << 7) | (byte & 127)
            if not byte & 128:
                return value
        raise ValueError('Invalid LCF integer')

    def done(self):
        if self.pos != len(self.data):
            raise ValueError(f'{len(self.data)-self.pos} unread bytes')


def signed(value):
    return value - 2**32 if value >= 2**31 else value


def packed(data, fmt):
    size = struct.calcsize(fmt)
    if len(data) % size:
        raise ValueError('Misaligned packed array')
    return [v[0] for v in struct.iter_unpack('<' + fmt, data)]


class Decoder:
    def __init__(self, encoding='cp1252'):
        self.encoding = encoding
        self.fields = collections.defaultdict(dict)
        for row in rows('fields.csv') + rows('fields_easyrpg.csv'):
            if row['Index']:
                self.fields[row['Structure']][int(row['Index'], 0)] = row
        self.indexed = {r['Structure'] for r in rows('structs.csv') if r['Index available?'] == '1'}
        self.codes = {int(r['Index']): r['Value'] for r in rows('enums.csv') + rows('enums_easyrpg.csv')
                      if r['Structure'] == 'EventCommand' and r['Entry'] == 'Code'}
        self.moves = {int(r['Index']): r['Value'] for r in rows('enums.csv')
                      if r['Structure'] == 'MoveCommand' and r['Entry'] == 'Code'}
        self.opaque = collections.Counter()

    def string(self, data):
        # surrogateescape preserves otherwise undefined Windows-1252 bytes in JSON.
        return data.decode(self.encoding, errors='surrogateescape')

    def array(self, kind, reader):
        result = []
        for _ in range(reader.integer()):
            ident = reader.integer() if kind in self.indexed else None
            obj = self.structure(kind, reader)
            if ident is not None:
                obj = {'id': ident, **obj}
            result.append(obj)
        return result

    def structure(self, kind, reader):
        out = {}
        while reader.pos < len(reader.data):
            tag = reader.integer()
            if not tag:
                break
            data = reader.take(reader.integer())
            row = self.fields[kind].get(tag)
            if row is None:
                key = f'_unknown_{tag}'
                out[key] = self.raw(data, kind + '.' + key)
            else:
                key = row['Field'] + ('_size' if row['Size Field?'] == 't' else '')
                if key in out:
                    raise ValueError(f'Duplicate field {kind}.{key}')
                try:
                    out[key] = self.value('Int32' if row['Size Field?'] == 't' else row['Type'], data)
                except ValueError as exc:
                    raise ValueError(f'{kind}.{key} ({row["Type"]}, {len(data)} bytes): {exc}') from exc
        return out

    def raw(self, data, kind):
        self.opaque[kind] += 1
        return {'encoding': 'base64', 'type': kind, 'data': base64.b64encode(data).decode()}

    def commands(self, reader):
        out = []
        while reader.pos < len(reader.data):
            offset = reader.pos
            code = reader.integer()
            if code == 0:
                # RM writers may append zero padding after the sentinel.
                if any(reader.take(len(reader.data)-reader.pos)):
                    raise ValueError('Nonzero data after event-command sentinel')
                break
            indent = reader.integer()
            string = self.string(reader.take(reader.integer()))
            params = [signed(reader.integer()) for _ in range(reader.integer())]
            out.append({'index': len(out), 'byte_offset_in_command_stream': offset,
                        'code': code, 'name': self.codes.get(code, 'Unknown'),
                        'indent': indent, 'string': string, 'parameters': params})
        return out

    def value(self, kind, data):
        r = Reader(data)
        if kind == 'DatabaseVersion' and not data:
            return 0
        if kind in ('String', 'DBString'):
            return self.string(data)
        if kind == 'Vector<EventCommand>':
            return self.commands(r)
        if kind == 'Vector<MoveCommand>':
            result = []
            while r.pos < len(data):
                code = r.integer()
                name = self.moves.get(code, 'Unknown')
                cmd = {'code': code, 'name': name, 'parameters': []}
                if name in ('change_graphic', 'play_sound_effect'):
                    cmd['string'] = self.string(r.take(r.integer()))
                n = 3 if name == 'play_sound_effect' else int(name in ('switch_on', 'switch_off', 'change_graphic'))
                cmd['parameters'] = [signed(r.integer()) for _ in range(n)]
                result.append(cmd)
            return result
        if kind.startswith('Array<'):
            result = self.array(kind[6:-1].split(':')[0], r)
        elif kind == 'Parameters':
            values = packed(data, 'h')
            if len(values) % 6:
                raise ValueError('Invalid actor parameters')
            n = len(values)//6
            return {key: values[i*n:(i+1)*n] for i, key in enumerate(('maxhp','maxsp','attack','defense','spirit','agility'))}
        elif kind == 'Equipment':
            values = packed(data, 'h')
            if len(values) != 5:
                raise ValueError('Invalid equipment')
            return dict(zip(('weapon_id','shield_id','armor_id','helmet_id','accessory_id'), values))
        elif kind == 'Rect':
            return dict(zip(('left','top','right','bottom'), packed(data, 'I'), strict=True))
        elif kind.startswith('Vector<') or kind in ('DBArray<Int32>', 'DBBitArray'):
            inner = kind[7:-1] if kind.startswith('Vector<') else kind
            fmt = {'UInt8':'B','Boolean':'B','Int16':'h','UInt32':'I'}.get(inner, 'i')
            if kind == 'DBBitArray':
                fmt = 'B'  # LCF serializes one byte per boolean, not packed bits.
            return packed(data, fmt)
        elif kind in self.fields:
            result = self.structure(kind, r)
        elif kind.endswith('_Flags'):
            return int.from_bytes(data, 'little')
        elif kind == 'Double':
            return struct.unpack('<d', data)[0]
        elif kind in ('Int8','UInt8'):
            return struct.unpack('b' if kind == 'Int8' else 'B', data)[0]
        elif kind == 'UInt32':
            return struct.unpack('<I', data)[0]
        elif kind in ('Int32','Boolean','DatabaseVersion') or kind.startswith(('Ref<','Enum<','Count<')) or kind.endswith('_Flags'):
            result = signed(r.integer())
            if kind == 'Boolean':
                result = bool(result)
        else:
            return self.raw(data, kind)
        r.done()
        return result

    def file(self, path, kind, header):
        r = Reader(path.read_bytes())
        actual = r.take(r.integer()).decode('ascii')
        if actual != header:
            raise ValueError(f'{path}: expected {header}, got {actual}')
        if kind == 'TreeMap':
            obj = {'maps': self.array('MapInfo', r)}
            obj['tree_order'] = [r.integer() for _ in range(r.integer())]
            obj['active_node'] = r.integer()
            obj['start'] = self.structure('Start', r)
        else:
            obj = self.structure(kind, r)
        r.done()
        return obj


def write(path, value):
    path.parent.mkdir(parents=True, exist_ok=True)
    # ASCII escapes also make undecodable source bytes losslessly representable.
    path.write_text(json.dumps(value, indent=2, ensure_ascii=True) + '\n')


def jsonl(path, values):
    path.write_text(''.join(json.dumps(v, ensure_ascii=True) + '\n' for v in values))


def sha(path):
    h = hashlib.sha256()
    with path.open('rb') as f:
        for block in iter(lambda: f.read(1024*1024), b''):
            h.update(block)
    return h.hexdigest()


def main():
    ap = argparse.ArgumentParser(description=__doc__)
    ap.add_argument('--game', type=Path, default=ROOT.parent/'funnyguyrpg/game')
    ap.add_argument('--assets', type=Path, default=Path('/mnt/hdd/assets'))
    ap.add_argument('--out', type=Path, default=ROOT/'data/rpg')
    ap.add_argument('--encoding', default='cp1252')
    args = ap.parse_args()
    d = Decoder(args.encoding)
    source = args.game.resolve()
    out = args.out.resolve()
    if out == source or source in out.parents:
        raise ValueError('Output must be outside original game')
    out.mkdir(parents=True, exist_ok=True)
    db = d.file(source/'RPG_RT.ldb','Database','LcfDataBase')
    tree = d.file(source/'RPG_RT.lmt','TreeMap','LcfMapTree')
    write(out/'database.json', db)
    write(out/'map_tree.json', tree)
    names = {m['id']:m.get('name','') for m in tree['maps']}
    maps = []
    map_index = []
    for p in sorted(source.glob('Map*.lmu')):
        ident = int(p.stem[3:])
        m = d.file(p,'Map','LcfMapUnit')
        m.update(id=ident, name=names.get(ident,''), source=p.name)
        for layer in ('lower_layer','upper_layer'):
            if layer in m and len(m[layer]) != m.get('width',20)*m.get('height',15):
                raise ValueError(f'{p}: incorrect tile count for {layer}')
        maps.append(m)
        write(out/'maps'/f'{p.stem}.json', m)
        map_index.append({k:m[k] for k in ('id','name','source','width','height','chipset_id') if k in m} |
                         {'event_count':len(m.get('events',[]))})
    write(out/'maps.json', map_index)
    for field in ('actors','skills','items','enemies','switches','variables','commonevents','troops','chipsets','battleranimations'):
        write(out/(field+'.json'), db.get(field,[]))
    streams = []
    for m in maps:
        for e in m.get('events',[]):
            for page in e.get('pages',[]):
                streams.append(({'source':m['source'],'map_id':m['id'],'map_name':m['name'],
                                 'event_id':e['id'],'event_name':e.get('name',''),'page_id':page['id'],
                                 'condition':page.get('condition',{}),'trigger':page.get('trigger',0)}, page.get('event_commands',[])))
    for e in db.get('commonevents',[]):
        streams.append(({'source':'RPG_RT.ldb','common_event_id':e['id'],'event_name':e.get('name',''),
                         'trigger':e.get('trigger',5),'switch_flag':e.get('switch_flag',False),
                         'switch_id':e.get('switch_id',1)}, e.get('event_commands',[])))
    for troop in db.get('troops',[]):
        for page in troop.get('pages',[]):
            streams.append(({'source':'RPG_RT.ldb','troop_id':troop['id'],'page_id':page['id'],
                             'condition':page.get('condition',{})}, page.get('event_commands',[])))
    commands, dialogue, transfers, logic = [], [], [], []
    for context, stream in streams:
        for c in stream:
            record = context | c
            record['source_id'] = ':'.join(str(context.get(k,'')) for k in ('source','map_id','common_event_id','troop_id','event_id','page_id')) + ':' + str(c['index'])
            commands.append(record)
            if c['name'] in ('ShowMessage','ShowMessage_2','ShowChoice','ShowChoiceOption'):
                dialogue.append(record | {'speaker':None})
            if c['name'] == 'Teleport':
                transfers.append(record | {'destination_map_id':c['parameters'][0] if c['parameters'] else None})
            if c['name'].startswith(('ControlSwitch','ControlVar','ConditionalBranch','ElseBranch','EndBranch','Call')):
                logic.append(record)
    jsonl(out/'commands.jsonl', commands)
    jsonl(out/'dialogue.jsonl', dialogue)
    jsonl(out/'logic.jsonl', logic)
    write(out/'transfers.json', transfers)
    (out/'dialogue.txt').write_text('\n'.join(f"[{r['source_id']}] {r.get('map_name',r.get('event_name',''))} | {r['name']} | {r['string']}" for r in dialogue)+'\n', errors='backslashreplace')
    # Inventory game media without copying it or asserting ownership based on location.
    assets = []
    for folder in sorted(source.iterdir()):
        if folder.is_dir():
            for p in sorted(folder.rglob('*')):
                if p.is_file():
                    assets.append({'source':p.relative_to(source).as_posix(),'category':folder.name,
                                   'bytes':p.stat().st_size,'sha256':sha(p),'ownership':'unreviewed'})
    write(out/'assets.json', assets)
    def asset_ref(category, name):
        matches = [a['source'] for a in assets if a['category'] == category and
                   Path(a['source']).stem.casefold() == name.casefold()]
        return {'name':name, 'category':category, 'candidates':matches,
                'status':'resolved' if len(matches)==1 else 'ambiguous' if matches else 'missing'}
    characters = []
    for actor in db.get('actors',[]):
        animation_id = actor.get('battler_animation',1)
        animation = next((a for a in db.get('battleranimations',[]) if a['id']==animation_id),{})
        battle_names = sorted({pose.get('battler_name','') for pose in animation.get('poses',[]) if pose.get('battler_name')})
        characters.append({'id':actor['id'], 'name':actor.get('name',''), 'source':'RPG_RT.ldb',
                           'database_selector':{'collection':'actors','id':actor['id']},
                           'charset':asset_ref('CharSet',actor.get('character_name','')),
                           'character_index':actor.get('character_index',0),
                           'faceset':asset_ref('FaceSet',actor.get('face_name','')),
                           'face_index':actor.get('face_index',0),
                           'battler_animation_id':animation_id,
                           'battle_sheets':[asset_ref('BattleCharSet',name) for name in battle_names],
                           'skills':actor.get('skills',[]), 'initial_equipment':actor.get('initial_equipment',{}),
                           'record_file':'actors.json', 'identity_scope':'RPG actor; no cross-era identity inferred'})
    write(out/'characters.json',characters)
    messages = []
    for record in dialogue:
        if record['name'] not in ('ShowMessage','ShowMessage_2'):
            continue
        same_stream = messages and all(messages[-1].get(k)==record.get(k) for k in
                            ('source','map_id','event_id','page_id','common_event_id','troop_id'))
        if record['name']=='ShowMessage_2' and same_stream and messages[-1]['command_indices'][-1]+1==record['index']:
            messages[-1]['lines'].append(record['string'])
            messages[-1]['command_indices'].append(record['index'])
        else:
            messages.append({k:v for k,v in record.items() if k not in ('string','index')} |
                            {'command_indices':[record['index']], 'lines':[record['string']]})
    for message in messages:
        message['text'] = '\n'.join(message['lines'])
        # Label is evidence from literal text, not a verified actor identity.
        label = re.match(r'^([^:\\<>\n]{1,40}):\s', message['text'])
        message['explicit_speaker_label'] = label.group(1) if label else None
    jsonl(out/'messages.jsonl',messages)
    write(out/'speakers.json',dict(sorted(collections.Counter(m['explicit_speaker_label'] for m in messages if m['explicit_speaker_label']).items())))
    sources = sorted(source.glob('Map*.lmu')) + [source/'RPG_RT.ldb',source/'RPG_RT.lmt']
    write(out/'sources.json', [{'source':p.name,'bytes':p.stat().st_size,'sha256':sha(p)} for p in sources])
    # Search historical references by basename; retain all candidates after flattening.
    legacy = collections.defaultdict(list)
    if args.assets.is_dir():
        for directory, dirs, files in os.walk(args.assets):
            dirs[:] = sorted(n for n in dirs if n not in {'Trash','.Trash','.git','.agents','.codex'})
            for name in sorted(files):
                p = Path(directory)/name
                legacy[p.name.casefold()].append(p.as_posix())
    write(out/'legacy_asset_index.json',dict(legacy))
    imported = collections.defaultdict(list)
    for manifest_path in sorted((ROOT/'references/imports').glob('*-manifest.json')):
        for entry in json.loads(manifest_path.read_text()).get('files',[]):
            destination = ROOT/entry['repo_path']
            if destination.is_file():
                imported[Path(entry['source']).name.casefold()].append(str(destination))
    def candidates_for(name):
        return sorted(set(legacy.get(name.casefold(),[])+imported.get(name.casefold(),[])))
    historical = []
    old_manifest = ROOT/'references/legacy_text/asset_manifest_before_flattening.csv'
    if old_manifest.exists():
        with old_manifest.open(newline='') as f:
            for row in csv.DictReader(f):
                if row['source'] != 'legacy_dump':
                    continue
                candidates = candidates_for(Path(row['path']).name)
                historical.append({'document':old_manifest.relative_to(ROOT).as_posix(),
                    'historical_reference':row['path'],'candidates':candidates,
                    'status':'unique_basename_candidate' if len(candidates)==1 else 'ambiguous' if candidates else 'unresolved'})
    for folder in ('references','docs'):
        for doc in sorted((ROOT/folder).rglob('*.md')):
            for old in sorted(set(re.findall(r"/mnt/creative/funnyguy/[^`\n]+",doc.read_text(errors='replace')))):
                basename = Path(old.rstrip('/')).name
                candidates = candidates_for(basename)
                historical.append({'document':doc.relative_to(ROOT).as_posix(),'historical_reference':old,
                                   'candidates':candidates,'status':'unique_basename_candidate' if len(candidates)==1 else
                                   'ambiguous' if candidates else 'unresolved'})
    write(out/'legacy_reference_resolution.json',historical)
    summary = {'schema_version':1,'liblcf_revision':REVISION,'encoding':args.encoding,
               'game_root':str(source),'legacy_assets_root':str(args.assets.resolve()),
               'maps':len(maps),'event_pages':len(streams),'commands':len(commands),
               'dialogue_records':len(dialogue),'message_lines':sum(r['name'] in ('ShowMessage','ShowMessage_2') for r in dialogue),
               'message_blocks':len(messages),'actors':len(db.get('actors',[])),'skills':len(db.get('skills',[])),
               'items':len(db.get('items',[])),'enemies':len(db.get('enemies',[])),
               'game_asset_files':len(assets),'legacy_asset_files':sum(map(len,legacy.values())),
               'legacy_reference_status':dict(collections.Counter(r['status'] for r in historical)),
               'opaque_fields':dict(d.opaque),'unknown_command_codes':sorted({r['code'] for r in commands if r['name']=='Unknown'})}
    write(out/'summary.json',summary)
    print(json.dumps(summary,indent=2))


if __name__ == '__main__':
    main()
