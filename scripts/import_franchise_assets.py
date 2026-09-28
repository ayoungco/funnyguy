#!/usr/bin/env python3
"""Copy a curated import plan, extract text, verify hashes, then optionally trash originals.

Never infers source scope from an extension scan. Uses an explicit reviewed plan.
See docs/asset-import.md for commands and recovery instructions.
"""
import argparse
import hashlib
import json
from pathlib import Path
import shutil
import subprocess
import xml.etree.ElementTree as ET
import zipfile

ROOT = Path(__file__).resolve().parents[1]
W = '{http://schemas.openxmlformats.org/wordprocessingml/2006/main}'
T = '{urn:oasis:names:tc:opendocument:xmlns:text:1.0}'


def digest(path):
    return hashlib.sha256(path.read_bytes()).hexdigest()


def save(path, obj):
    tmp = path.with_suffix('.tmp')
    tmp.write_text(json.dumps(obj, indent=2, ensure_ascii=False)+'\n')
    tmp.replace(path)


def safe_path(root, relative):
    p = root / relative
    if p.is_symlink() or not p.resolve().is_relative_to(root.resolve()):
        raise ValueError(f'Unsafe path: {p}')
    return p


def extract(path):
    ext = path.suffix.lower()
    if ext == '.doc':
        run = subprocess.run(['antiword','-m','UTF-8.txt',str(path)],capture_output=True,check=True)
        return run.stdout.decode('utf-8'), 'antiword UTF-8; textual content, not layout'
    if ext == '.docx':
        sections=[]
        with zipfile.ZipFile(path) as z:
            names=['word/document.xml']+sorted(n for n in z.namelist() if
                n.startswith(('word/header','word/footer')) and n.endswith('.xml'))
            names += [n for n in ('word/footnotes.xml','word/endnotes.xml','word/comments.xml') if n in z.namelist()]
            for name in names:
                tree=ET.fromstring(z.read(name))
                lines=[]
                for paragraph in tree.iter(W+'p'):
                    text=''.join(e.text or '' if e.tag in (W+'t',W+'delText') else
                                 '\t' if e.tag==W+'tab' else '\n' if e.tag in (W+'br',W+'cr') else ''
                                 for e in paragraph.iter())
                    if text.strip(): lines.append(text)
                if lines: sections.append(f'[{name}]\n'+'\n'.join(lines))
        return '\n\n'.join(sections), 'OOXML paragraph text, including headers/footers/notes/comments and tracked deleted text if present; not layout'
    if ext == '.odt':
        with zipfile.ZipFile(path) as z:
            tree=ET.fromstring(z.read('content.xml'))
        def text(node):
            if node.tag==T+'s': return ' '*int(node.get(T+'c','1'))
            if node.tag==T+'tab': return '\t'
            if node.tag==T+'line-break': return '\n'
            return (node.text or '')+''.join(text(c)+(c.tail or '') for c in node)
        return '\n'.join(text(e) for e in tree.iter() if e.tag in (T+'p',T+'h')), 'ODT content.xml paragraph and heading text; not layout'
    data=path.read_bytes()
    for encoding in ('utf-8-sig','cp1252','latin-1'):
        try: return data.decode(encoding), f'Plain text decoded as {encoding}; original bytes retained separately'
        except UnicodeDecodeError: pass
    raise ValueError('No text decoder')


def verify_entry(entry, source_root, trash_root):
    stored=ROOT/entry['repo_path']
    if digest(stored)!=entry['sha256']: raise ValueError(f'Repository copy changed: {stored}')
    source=safe_path(source_root,entry['source'])
    trash=safe_path(trash_root,entry['source'])
    locations=[p for p in (source,trash) if p.exists()]
    if len(locations)!=1: raise ValueError(f'Expected exactly one original/trash copy: {entry["source"]}')
    if digest(locations[0])!=entry['sha256']: raise ValueError(f'Original changed: {locations[0]}')
    if entry.get('text_path') and digest(ROOT/entry['text_path'])!=entry['text_sha256']:
        raise ValueError(f'Text extraction changed: {entry["text_path"]}')
    return source,trash


def main():
    ap=argparse.ArgumentParser(description=__doc__)
    ap.add_argument('--plan',type=Path,default=ROOT/'references/imports/hdd-2026-09-27-plan.json')
    ap.add_argument('action',choices=['prepare','verify','trash','restore'])
    args=ap.parse_args()
    plan=json.loads(args.plan.read_text())
    manifest_path=args.plan.with_name(plan['batch']+'-manifest.json')
    source_root=Path(plan['source_root'])
    trash_root=Path(plan['trash_root'])
    if args.action=='prepare':
        if manifest_path.exists(): raise ValueError('Manifest already exists; verify it instead of overwriting')
        entries=[]
        for item in plan['files']:
            src=safe_path(source_root,item['source'])
            if not src.is_file(): raise ValueError(f'Missing source: {src}')
            sha=digest(src)
            base='assets/reference/hdd' if item['kind']=='image' else 'references/source_documents/hdd'
            dst=safe_path(ROOT/base,item['source'])
            if dst.exists() and digest(dst)!=sha: raise ValueError(f'Destination collision: {dst}')
            dst.parent.mkdir(parents=True,exist_ok=True)
            if not dst.exists(): shutil.copy2(src,dst)
            if digest(dst)!=sha: raise ValueError(f'Copy failed: {src}')
            entry=item|{'repo_path':dst.relative_to(ROOT).as_posix(),'bytes':src.stat().st_size,
                        'sha256':sha,'state':'copied','trash_path':str(trash_root/item['source'])}
            if item['kind']=='document':
                content,method=extract(dst)
                if not content.strip() or '\x00' in content: raise ValueError(f'Empty/binary text extraction: {src}')
                text_path=safe_path(ROOT/'references/legacy_text/hdd_2026',item['source']+'.txt')
                text_path.parent.mkdir(parents=True,exist_ok=True)
                text_path.write_text(content.rstrip()+'\n',encoding='utf-8')
                entry.update(text_path=text_path.relative_to(ROOT).as_posix(),text_sha256=digest(text_path),
                             extraction_method=method,text_characters=len(content))
            entries.append(entry)
        manifest={'batch':plan['batch'],'source_root':str(source_root),'trash_root':str(trash_root),'files':entries}
        save(manifest_path,manifest)
    else:
        manifest=json.loads(manifest_path.read_text())
        if manifest['source_root']!=str(source_root) or manifest['trash_root']!=str(trash_root):
            raise ValueError('Plan/manifest root mismatch')
        # Verify the entire batch before the first move. Recovery is idempotent.
        for e in manifest['files']: verify_entry(e,source_root,trash_root)
        for entry in manifest['files']:
            source,trash=verify_entry(entry,source_root,trash_root)
            if args.action=='trash' and source.exists():
                trash.parent.mkdir(parents=True,exist_ok=True)
                if trash.exists(): raise ValueError(f'Trash collision: {trash}')
                source.rename(trash)
                if digest(trash)!=entry['sha256']: raise ValueError(f'Trash verification failed: {trash}')
                entry['state']='trashed'
                save(manifest_path,manifest)
            elif args.action=='restore' and trash.exists():
                source.parent.mkdir(parents=True,exist_ok=True)
                if source.exists(): raise ValueError(f'Restore collision: {source}')
                trash.rename(source)
                entry['state']='restored'
                save(manifest_path,manifest)
    print(json.dumps({'action':args.action,'files':len(manifest['files']),
                      'documents':sum(e['kind']=='document' for e in manifest['files']),
                      'images':sum(e['kind']=='image' for e in manifest['files']),
                      'original_bytes':sum(e['bytes'] for e in manifest['files']),
                      'manifest':str(manifest_path)},indent=2))


if __name__=='__main__': main()
