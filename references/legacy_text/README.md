# Legacy text material — full copies

Full-text copies of the small, plain-text/lore documents from
`/mnt/creative/funnyguy/Documents/`, pulled into git so this repo has
durable, versioned, full-fidelity access to the actual source text —
`references/story_bible.md` only ever quoted/summarized from these, it
never carried the raw material itself.

This directory deliberately does **not** mirror the whole `Documents/`
folder — see `references/remix_material_audit.md` for the full audit and
what was excluded (legal/business contracts, personal `.eml`
correspondence, third-party academic/reference PDFs unrelated to Funny
Guy, and one confirmed-corrupt file). Nothing here required a NAS mount
after this copy — that's the point.

## Files

- `322.txt`, `325.txt` — unproduced comic scripts (see `story_bible.md`,
  "Unproduced material worth mining").
- `ideas.txt` — short raw gag-fragment list.
- `fgcomics_notebook.txt` — longer gag-fragment/non-sequitur list
  (converted from `FGComics Notebook.docx`).
- `fgcomics_idea_scratch.txt` — another short scratch idea list, previously
  undocumented (converted from `FGCOMICS.docx`).
- `design_notes_and_ideas.txt` — character ideas *and* the creator's own
  articulated craft philosophy for the comic ("The GRID can make things
  boring... don't be afraid to mix up the panes", "Humor first, story
  SECOND", etc.) — previously undocumented (converted from
  `Ideas_Notes.docx`). Directly useful for `references/remix_style.md`
  and any future generation-prompt work: this is the original author's
  own design intent, not inferred from the art.
- `fgcomics_history.txt`, `fgoac_history.txt` — the two history write-ups
  (converted from `.odt`/`.docx`).
- `fgcomics_character_bios.txt` — the bios doc `story_bible.md`'s cast
  section is drawn from (converted from `.docx`).
- `character_bios_2012_draft.txt` — an earlier/parallel draft of the same
  bios (`Character Bios 2012.odt`, previously undocumented) — mostly
  redundant with `fgcomics_character_bios.txt` but kept since it carries
  a bit of framing text (the "Repair Man's Own(tm)" gag header) not
  present in the other copy.
- `misc_information_2012.txt` — source for the "Funnyland" setting quote
  in `story_bible.md`.
- `assimilation_document.txt` — a short in-universe mock-legal "contract"
  text (Repair Man becoming Funny Guy Comix Corporation property) —
  previously undocumented; this is the actual text behind the "an
  in-universe contract was drafted and signed" line in `story_bible.md`'s
  Origin section.
- `fgcomics_news_2005-2007.md` — 13 public news-post announcements from
  the old fgcomics.com homepage (site history, FGRPG version history, the
  "p3stil" hacker defacement already flagged in
  `remix_material_audit.md`), reformatted from a raw IPB news-module
  export (`FGComics News (2005).txt`) into plain dated entries. Author
  email addresses and avatar-image URLs from the raw export are dropped
  as export-format cruft, not because the post content itself is
  sensitive — the posts were public.
- `fgcomics_collections.sql` — the official book/season structure dump
  `story_bible.md`'s table is built from.

## Not copied here (checked, deliberately excluded)

- `The Funny Guy Comix ___ what.sql` (125KB) — a single-table dump
  (`number`, `page`, `filename`, `title`, dialogue transcript, author,
  date) that's redundant with data `../../funnyguycomics` already carries
  per-comic in its own frontmatter. Confirmed via its `CREATE TABLE` and a
  sample row, not assumed.
- `circ03.pdf` / `circ44.pdf` — U.S. Copyright Office circulars (public
  reference material the creator downloaded, not Funny Guy content).
- `grohacthesis.pdf` — an unrelated third-party academic thesis
  ("Copyright and the Economy of Webcomics" by George Rohac Jr.) that
  ended up in the same folder — not Funny Guy material.
- `Finally!!!!.eml`, `I don't geet it!!.eml` — personal correspondence.
- Contracts, copyright/ownership docs, the employee sheet, return-policy
  docs — business/legal, not creative.
- `Funny Guy Dialogue.docx` — confirmed corrupt (all-null-byte content
  despite a valid file size) in an earlier audit pass; unrecoverable.
- `Future Steam Release.md` — a separate, more recent (2026) research
  thread about RPG Maker Steam redistribution, not comic lore.
## OCR'd separately (scanned, no text layer)

- `fgcomics_ideas_ocr.md` (from `FGComics Ideas.pdf`, 12 pages) and
  `fgcomics_notes_ocr.md` (from `FGComics Notes.pdf`, 7 pages) — OCR'd via
  the local `qwen2.5vl:7b` VLM (GPU, through Ollama), illegible text
  marked `[illegible]` rather than guessed. `fgcomics_notes_ocr.md`'s
  p-4 is worth reading directly: a primary-source list of comedic
  influences (Weird Al, Steven Wright, Demetri Martin, Bill Hicks, George
  Carlin, Emo Philips, Tommy Blacha, Danny Antonucci, etc.) that confirms
  and extends `references/remix_style.md`'s Stephen Wright framing with an
  actual source instead of an inferred comparison.
- Ran at default Ollama context (4096 tokens) after the first attempt at
  150 DPI hit a `400 exceed_context_size_error` on full-page scans
  combined with a long prompt — re-rendering at 100 DPI (for `Notes.pdf`)
  fixed it without needing a larger context window. Worth knowing for any
  future OCR pass over more scanned pages: raising `num_ctx` instead would
  also fix it, but ballooned the model's resident memory enough to get a
  background OCR run killed by a low-memory watchdog on this machine (15GB
  RAM, already running a desktop session) — lowering the input resolution
  is the safer fix here, not a bigger context window.

## hdd_import/

Text pulled from `/mnt/hdd/funnyguy` (a second legacy dump): FGRPG help
file, in-game guide pages (`rpg_guide/`), RPG Maker text-code and
obfuscation notes, the FGRPG ad copy (author email redacted), the old
forum rules, and `future_steam_release.md` (Steam redistribution research).
The comic-transcript SQL dumps there were not copied: their content is
already in `../funnyguycomics` (`data/comics.json`, `data/fgcomics.sql`).
