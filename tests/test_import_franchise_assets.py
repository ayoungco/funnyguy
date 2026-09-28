import contextlib
import importlib.util
import io
import json
from pathlib import Path
import sys
import tempfile
import unittest
from unittest.mock import patch

spec=importlib.util.spec_from_file_location('import_franchise_assets',Path(__file__).resolve().parents[1]/'scripts/import_franchise_assets.py')
m=importlib.util.module_from_spec(spec)
spec.loader.exec_module(m)


class MigrationTests(unittest.TestCase):
    def setUp(self):
        self.temp=tempfile.TemporaryDirectory()
        self.addCleanup(self.temp.cleanup)
        self.base=Path(self.temp.name)
        self.repo=self.base/'repo'; self.repo.mkdir()
        self.source=self.base/'assets'; self.source.mkdir()
        self.original=self.source/'notes.txt'
        self.original.write_bytes(b'Funny Guy\r\nOriginal notes\r\n')
        self.plan=self.repo/'batch-plan.json'
        self.plan.write_text(json.dumps({'batch':'test','source_root':str(self.source),
            'trash_root':str(self.source/'Trash/batch'),
            'files':[{'source':'notes.txt','kind':'document'}]}))

    def run_action(self,action):
        with patch.object(m,'ROOT',self.repo),patch.object(sys,'argv',['importer','--plan',str(self.plan),action]),contextlib.redirect_stdout(io.StringIO()):
            m.main()

    def test_copy_trash_verify_restore_and_idempotency(self):
        original=self.original.read_bytes()
        self.run_action('prepare')
        self.assertEqual(self.original.read_bytes(),original)
        self.run_action('verify')
        for _ in range(2): self.run_action('trash')
        self.assertFalse(self.original.exists())
        self.assertEqual((self.source/'Trash/batch/notes.txt').read_bytes(),original)
        self.run_action('verify')
        for _ in range(2): self.run_action('restore')
        self.assertEqual(self.original.read_bytes(),original)
        self.run_action('verify')

    def test_changed_repository_copy_blocks_trash(self):
        self.run_action('prepare')
        (self.repo/'references/source_documents/hdd/notes.txt').write_text('changed')
        with self.assertRaises(ValueError): self.run_action('trash')
        self.assertTrue(self.original.exists())
        self.assertFalse((self.source/'Trash/batch/notes.txt').exists())

    def test_collision_blocks_move(self):
        self.run_action('prepare')
        trash=self.source/'Trash/batch/notes.txt'; trash.parent.mkdir(parents=True)
        trash.write_text('other file')
        with self.assertRaises(ValueError): self.run_action('trash')
        self.assertTrue(self.original.exists())
        self.assertEqual(trash.read_text(),'other file')

    def test_path_escape_rejected(self):
        with self.assertRaises(ValueError): m.safe_path(self.repo,'../outside')


if __name__=='__main__': unittest.main()
