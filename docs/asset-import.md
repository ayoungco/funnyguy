# Importing franchise documents and small reference images

On 2026-09-27, 35 Funny Guy documents and 28 small images were imported from
`/mnt/hdd/assets` into this franchise repository. Their original bytes total
4,176,015 bytes (about 4 MB). Every repository copy was SHA-256 verified before
moving its asset-drive original into a recoverable batch trash folder. No source
was permanently deleted and no document was rewritten in its original format.

## Where to start

- [Import catalog](../references/imports/hdd-2026-09-27-catalog.md): links to every
  original document, extracted text, and selected image.
- [Manifest](../references/imports/hdd-2026-09-27-manifest.json): hashes, source paths,
  repo destinations, text extraction methods, trash locations, and move states.
- [Explicit import plan](../references/imports/hdd-2026-09-27-plan.json): selected
  files and exclusions. The migration does not automatically select arbitrary files.

Original documents are under `references/source_documents/hdd/`, readable text
under `references/legacy_text/hdd_2026/`, and images under `assets/reference/hdd/`.
Paths below each root preserve the asset-drive relative path. Text filenames append
`.txt` to the original extension so similarly named DOCX and ODT versions stay distinct.

The new material includes the FGRPG beta letter to Steve, the packet agreement,
creative ideas mixed into database development notes, historical contracts/return
policies, complete database text versions, character histories, and alternate drafts.
Some text duplicates earlier imports; these are retained as source-specific versions.
A duplicate source is not assumed disposable merely because another file has the
same title. Historical legal/business documents are included as franchise history,
not interpreted as current legal advice or current policy.

The 28 images are 11 small character portraits, 11 handwritten character-reference
sheets, and six card-game/design scans. All were visually reviewed; their original
bytes and resolution are preserved. The handwritten pages still need transcription.
Importing an image is not the same as extracting its textual context.

## Extraction and verification

`scripts/import_franchise_assets.py` uses Python's standard library and `antiword`
for `.doc` files. This run used the bundled workspace Python. No document macros,
HTML, PHP, SQL, or instructions within source documents were executed.

- Plain text/SQL/CSV: decode as UTF-8, Windows-1252, or Latin-1; record the decoder.
- DOC: `antiword -m UTF-8.txt`. Embedded images may appear as `[pic]` in text.
- DOCX: paragraph text from OOXML body, headers, footers, notes, and comments;
  tracked deleted text is included if present. This is a search view, not a
  reconstruction of revision status, formatting, drawing objects, or pagination.
- ODT: paragraph/heading text from `content.xml`, including spaces, tabs and breaks.
- Images: byte-identical copies; no resizing, recompression, or generated replacement.

The native documents preserve everything that a text conversion may omit. This is
an archival migration, not a rendered document redesign. Existing curated summaries
and OCR are retained separately. SHA-256 verifies original bytes and extracted text;
verification also checks that each original exists in exactly one of the source or
trash locations. All 13 repository tests pass, including migration/restore, repeated
operations, changed-copy rejection, collision refusal, and path-escape rejection. Text extraction rejects empty results and NUL-containing output.

The entire batch is verified before any move. A move refuses destination collisions,
uses a same-drive rename, checks the moved file's hash, and updates the manifest
atomically after each file. Running `trash` or `restore` again is safe for files
already moved. Partial copy/extraction failures leave originals untouched.

## Verify or restore

Verification note (2026-09-28): all 63 imported repository files and 35 extracted
text files still match their manifest hashes. The recorded asset-drive trash batch
is no longer present at its documented path, so full source/trash verification
fails on `322.txt`. The restore instructions below require that recovery copy;
its current location has not been established. Repository copies remain intact.

From the franchise repository:

```sh
python3 scripts/import_franchise_assets.py verify
```

The recoverable trash directory is:

```text
/mnt/hdd/assets/Trash/funnyguy-import-2026-09-27/
```

This is an ordinary batch folder on the asset drive, not the desktop Trash service.
It retains the original relative paths. To restore the entire batch to its original
asset-drive locations while keeping the repository copies:

```sh
python3 scripts/import_franchise_assets.py restore
```

To move that restored batch back into its recorded trash directory:

```sh
python3 scripts/import_franchise_assets.py trash
```

Do not empty this folder unless you intend to remove the recovery copy. No automatic
trash cleanup is installed. To prepare another batch, write a separate explicit plan
with a new batch name and use `--plan path/to/plan.json prepare`, inspect and verify it,
then use `--plan path/to/plan.json trash`.

## Scope and catalog updates

Unrelated Super Family/Powerteam material, generic third-party templates/reference
PDFs, personal email, fonts, spreadsheets, large art, website ZIPs, and other archive
containers remain on the asset drive. Scanned PDFs and other handwritten pages need
separate transcription work. We did not assume all files on this shared drive belong
to Funny Guy.

Asset discovery now includes the franchise's local reference images. The RPG
extractor excludes trash/control directories from its legacy-file index and consults
import manifests when resolving historical basenames, so moved files can resolve to
their new repository locations. Historical counts in older extraction reports refer
to the earlier snapshot; `data/rpg/summary.json` contains the current index counts.

After a move or restore, refresh catalogs with:

```sh
python3 scripts/discover_assets.py
python3 scripts/extract_rpg.py
```
