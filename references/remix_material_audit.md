# Legacy dump: usable-material audit

`scripts/discover_assets.py` only catalogs image/design extensions and
prunes several top-level folders entirely (see `docs/progress.md`,
2026-08-28 entry) — `references/asset_manifest.csv` was never a full
inventory of `/mnt/creative/projects/funnyguy`. This is the fuller pass,
done specifically to find material worth pulling into generative remix
work (new comic-panel generations, mashup video, alt-dialogue, etc.), not
just images. Full top-level byte/file counts are reproducible with
`os.walk` over the mount if this needs re-running; not worth a
permanent script for a one-time audit.

## High-value for remix, not currently touched by any script

- **`Raw/*.psd` (368 files, 5.3GB)** — the layered originals behind
  `funnyguycomics/static/comics/*.png`, at ~2.6x the pixel resolution of
  the published web export (confirmed on `020_barney`: 2393x1816 native
  vs 900x683 published — see `docs/progress.md`, 2026-08-29 resolution
  entry). Layers are real and separable (`psd_tools`, already available
  in the ComfyUI venv): checked `020_barney.psd` and got 12 named layers
  — `Background`, `border`, and separate *text* layers per speech
  bubble/label (`Splat!`, `The End`, `Barney`, `potty!`, the page number,
  the copyright line, a smart-object book-title stamp). That means clean
  line art (no burned-in text) and the original dialogue text are both
  recoverable per-panel, independently — enables swapping in new
  dialogue over the real art, or isolating a character's line art for
  compositing into a new scene, instead of only being able to do
  whole-panel img2img against the flattened PNG like
  `generate_sdxl_img2img.py` currently does. Not all 368 will have
  identically clean layer structure (this checked one file) — treat as
  promising, not guaranteed, per comic.
- **`Playthrough/` (9.16GB: 7 `.mkv` + 3 `.mp4` video, `.wav` audio)** —
  actual `funnyguyrpg` gameplay capture. Usable as B-roll/backing footage
  for video remixes, or as a source of in-game VO/SFX if any exists in
  the audio tracks (not checked). Previously flagged as invisible to all
  tooling (2026-08-28 entry) and still is.
- **`Unsorted/` (4.86GB, pre-sorted into `png/`, `psd/`, `jpg/`, `gif/`,
  `ai/`, `svg/` subfolders)** — a mix of site-design assets (borders,
  palette textures, arrows) and, notably, filenames like `Funny Guy
  Sketches (3)2025_04_22_NNNN.png` / `Sketches (Batch 2)2025_04_22_*.png`
  — recently rescanned sketchbook pages (2025 timestamps), i.e. genuinely
  new, previously-uncatalogued character art, not just old site cruft.
  Worth a dedicated triage pass before generation work leans on it, since
  it's an unsorted mix of "reusable reference" and genuine junk (a
  "Turkish Hackers Response" PSD, in reference to a 2007 site defacement
  mentioned in `Documents/FGComics News (2005).txt`, is in here too).
- **`Documents/` text files** — see `references/story_bible.md` for the
  extracted content (character bios, history, the official book/season
  structure, and unproduced scripts in `322.txt`/`325.txt` worth
  illustrating fresh). Converted via `soffice --headless --convert-to
  txt` for `.docx`/`.odt`, `pdftotext` available for the `.pdf`s (not all
  opened this pass), plain `cat` for `.txt`/`.sql`. One file,
  `Funny Guy Dialogue.docx`, is corrupt (all-null bytes despite a valid
  5KB size) and unrecoverable.
- **Spin-off/side properties**, each a small self-contained lead:
  - `Books/Confiscated Repair Man Comic (1)/(2).gif` — Koven's original,
    pre-handoff Repair Man comics (wordless, per the history doc).
  - `Books/AOTD_#1..12.png` + `AOTD_backcover.png` — the full physical
    "Attack of the Drones" book art, separate from the regular
    `funnyguycomics` page scans.
  - `Books/FG_JAM_#1..3.png` — an otherwise-undocumented "FG Jam"
    crossover/collab pages.
  - `Books/Anti-Drug Campaign - Funny Guy Comics (1)/(2).{jpg,png}` — a
    PSA-style one-off.
  - `Card Game/*.gif` (6 scans) — a physical Funny Guy card game,
    completely undocumented anywhere else in this project.
  - `Covers/*.psd` (22 files) and `Logos/*.psd` (14 files) — book cover
    art and branding marks, useful for title cards/watermarks on
    generated video without redrawing logos from scratch.

## Present but lower priority

- **`Website Backups/`** (0.7GB: two full `.7z` site-file backups, a
  `Database Backups.zip`, an `FGComics - Website.zip`) — the `.7z`
  listing checked this pass is just PHP/template/image site files, no
  forum/guestbook content surfaced. The separate `Database Backups.zip`
  almost certainly holds the forum/guestbook database and wasn't opened
  this pass — flagging as the next thing to check if community
  reactions/history become relevant, not opened now since the on-disk
  `Documents/*.sql` dumps already cover the comics-and-collections data
  itself (see `references/story_bible.md`).
- **`Guide/`** — an old FGRPG manual (`index.htm`/`controls.htm`/
  `faq.htm`/`walkthrough.htm`), useful only as RPG-side flavor text, not
  comic content.
- **`Scans/`, `Art/`, `Comics/`** — smaller folders of scan/standalone
  art, not individually triaged this pass; nothing in the file listing
  stood out as higher-value than what `Raw/` and `Unsorted/` already
  cover.
- **`RPG_RT/`** (925 files, engine data — `.lmu` maps, `.wav`/`.mid`,
  `.ptn`) — the actual RPG Maker project, a different asset class
  entirely (game data, not art); relevant only if remix work ever
  extends into the RPG side.

## Explicitly not mined (business-sensitive or personal, not creative)

Legal/business documents (`Funny Guy Contract FINAL 2.doc`, `Funny Guy
Property Contract.doc`, `FGComics Copyright Ownership.docx`, `Joint
Ownership of Copyright Agreement.doc`, `Funny Guy Employee Sheet.xls`,
`Funny Guy Return Policy.doc` and its `[VOID]` predecessor) and personal
email correspondence (`Finally!!!!.eml`, `I don't geet it!!.eml`) exist
in `Documents/` and were seen in the file listing but not opened or
summarized here — not relevant to creative/remix context, and not this
project's business to be reading into a shared reference file.

## Format-handling notes for future scripts

- `.docx`/`.odt`: `soffice --headless --convert-to txt:"Text" --outdir
  <dir> <file>` — works well, batches multiple files in one invocation.
  Silently drops a file from its own output with no error line if
  something's wrong with it (happened with the corrupt `Funny Guy
  Dialogue.docx`) — check the output dir's file count against the input
  count, don't trust the process exit code alone.
- `.pdf`: `pdftotext` (poppler-utils), available, not exercised on the
  actual PDFs here yet.
- `.psd`: `psd_tools` (Python, already in the ComfyUI venv) reads layer
  names/kinds/visibility without needing Photoshop; `Image.open()` via
  Pillow also opens PSDs fine for a flattened read (used for the
  resolution comparison in `docs/progress.md`), just doesn't expose
  layers.
- `.sql`: plain MySQL dump text, no tooling needed — `grep`/`sed` for
  `CREATE TABLE`/`INSERT INTO` lines is enough for a small dump like
  these two (63 and 380 lines).
- `.7z`: `7z l <file>` lists contents without extracting — used to
  sanity-check `Website Backups/` before deciding not to fully extract
  it this pass.
