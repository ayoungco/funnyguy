import importlib.util
import json
from pathlib import Path
import unittest

ROOT = Path(__file__).resolve().parents[1]
spec = importlib.util.spec_from_file_location('extract_rpg', ROOT/'scripts/extract_rpg.py')
m = importlib.util.module_from_spec(spec)
spec.loader.exec_module(m)


def vint(n):
    n &= 0xffffffff
    parts = [n & 127]
    n >>= 7
    while n:
        parts.insert(0, (n & 127) | 128)
        n >>= 7
    return bytes(parts)


class BinaryTests(unittest.TestCase):
    def test_integer_boundaries_and_signed_values(self):
        for value in (0,127,128,16384,2**31-1,2**32-1):
            r=m.Reader(vint(value))
            self.assertEqual(r.integer(),value)
            r.done()
        self.assertEqual(m.signed(2**32-1),-1)

    def test_truncation_is_rejected(self):
        for payload in (b'',b'\x81',b'\xff'*6):
            with self.assertRaises(ValueError): m.Reader(payload).integer()
        with self.assertRaises(ValueError): m.Reader(b'ab').take(3)

    def test_command_text_parameters_and_sentinel(self):
        text=b'Hello \x93FG\x94!'
        payload=vint(10110)+vint(2)+vint(len(text))+text+vint(1)+vint(-1)+b'\0\0\0\0'
        commands=m.Decoder().commands(m.Reader(payload))
        self.assertEqual(commands[0]['string'],'Hello “FG”!')
        self.assertEqual(commands[0]['parameters'],[-1])
        self.assertEqual(commands[0]['indent'],2)
        with self.assertRaises(ValueError): m.Decoder().commands(m.Reader(payload+b'x'))

    def test_packed_data_and_flags(self):
        d=m.Decoder()
        self.assertEqual(d.value('Vector<Int16>',b'\x01\x00\xff\xff'),[1,-1])
        self.assertEqual(d.value('TroopPageCondition_Flags',b'\x01\x02'),513)
        self.assertEqual(d.value('DatabaseVersion',b''),0)
        with self.assertRaises(ValueError): d.value('Equipment',b'\0\0')

    def test_opaque_fields_and_undefined_encoding_preserved(self):
        d=m.Decoder()
        self.assertEqual(d.string(b'\x81').encode('cp1252',errors='surrogateescape'),b'\x81')
        data=vint(999)+vint(2)+b'xy'+b'\0'
        self.assertEqual(d.structure('Actor',m.Reader(data))['_unknown_999']['data'],'eHk=')


class CorpusTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.out=ROOT/'data/rpg'
        cls.summary=json.loads((cls.out/'summary.json').read_text())
        cls.commands=[json.loads(s) for s in (cls.out/'commands.jsonl').read_text().splitlines()]

    def test_source_hashes_and_all_maps(self):
        game=Path(self.summary['game_root'])
        for row in json.loads((self.out/'sources.json').read_text()):
            self.assertEqual(m.sha(game/row['source']),row['sha256'])
        self.assertEqual(len(list(game.glob('Map*.lmu'))),self.summary['maps'])
        self.assertEqual(len(list((self.out/'maps').glob('Map*.json'))),self.summary['maps'])

    def test_commands_and_grouped_messages_are_complete(self):
        self.assertEqual(len(self.commands),self.summary['commands'])
        self.assertEqual(len({r['source_id'] for r in self.commands}),len(self.commands))
        messages=[json.loads(s) for s in (self.out/'messages.jsonl').read_text().splitlines()]
        self.assertEqual(sum(len(r['lines']) for r in messages),self.summary['message_lines'])
        self.assertEqual(len(messages),self.summary['message_blocks'])
        self.assertFalse(self.summary['unknown_command_codes'])
        for r in messages: self.assertEqual(r['text'],'\n'.join(r['lines']))

    def test_character_assets_exist_and_are_unambiguous(self):
        game=Path(self.summary['game_root'])
        for c in json.loads((self.out/'characters.json').read_text()):
            for asset in [c['charset'],c['faceset']]+c['battle_sheets']:
                self.assertEqual(asset['status'],'resolved')
                self.assertTrue((game/asset['candidates'][0]).is_file())

    def test_transfer_destinations_and_known_source_text(self):
        maps={r['id'] for r in json.loads((self.out/'maps.json').read_text())}
        for t in json.loads((self.out/'transfers.json').read_text()):
            self.assertIn(t['destination_map_id'],maps)
        self.assertEqual(self.commands[0]['string'],'Gas Town')
        raw=Path(self.summary['game_root'],'Map0001.lmu').read_bytes()
        self.assertIn(b'Gas Town',raw)


if __name__=='__main__': unittest.main()
