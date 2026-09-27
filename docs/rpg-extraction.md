# Funny Guy RPG extraction

The `funnyguy/` repository is the franchise root: shared canon, extracted data,
asset catalogs, tools, and new-project planning belong here. `../funnyguyrpg`
remains the original game's source project; `../funnyguycomics` remains the comic
archive/site. This extraction reads those sources without moving or changing them.
Legacy assets now live at `/mnt/hdd/assets`. Old mounted-share paths are historical.

## Reproduce

Run from `funnyguy/`, with Python 3.10 or newer and no third-party dependencies:

```sh
python3 scripts/extract_rpg.py
python3 -m unittest discover -s tests -v
```

Optional locations and encoding:

```sh
python3 scripts/extract_rpg.py --game ../funnyguyrpg/game --assets /mnt/hdd/assets --out /tmp/fg-extraction --encoding cp1252
```

The script overwrites its generated outputs. Use a fresh output directory when
comparing revisions that remove maps; it does not delete stale map exports.
Do not put hand-authored canon corrections into generated files. Put them in
`references/`, retaining the original source IDs. The extractor never writes into
the game directory. SHA-256 hashes in `sources.json` identify the exact input corpus.

## Results

The initial extraction contains:

| Material | Count |
|---|---:|
| Map files | 315 |
| Map/common-event/troop command containers, including empty pages | 6,915 |
| Event commands | 17,098 |
| Message blocks | 2,945 |
| Message lines | 4,808 |
| Message/choice records | 5,266 |
| Actor records | 6 |
| Skill records | 190 |
| Item records | 140 |
| Enemy records | 121 |
| Direct teleport commands | 1,347 |
| Game media files | 523 |
| Files indexed under the current legacy asset root | 3,448 |

Database totals include unused slots and placeholder records; they are not counts
of unique authored characters or approved assets. Message lines include signs,
narration, repeated event-page text, and interface text. They are not all speech.
The six actors are Funny Guy, Junior, Sharp, Target, Olympian, and TimeTravel.

## Files to use

All outputs below are under `data/rpg/`.

| File | Purpose |
|---|---|
| `characters.json` | Six actors linked to existing charset, faceset, and battle sheets; sheet indices, initial equipment, and database skill learnings |
| `actors.json` | Full stored actor records, including stat curves |
| `messages.jsonl` | Multiline message blocks, source context, page conditions, command indices, and literal speaker-label candidates |
| `dialogue.jsonl` | Every message line and choice command individually |
| `dialogue.txt` | Searchable text with a source ID on every line |
| `commands.jsonl` | All map, common-event, and troop event commands, including non-dialogue strings |
| `logic.jsonl` | Switch/variable operations, conditionals, and calls; a filtered command index, not a solved dependency graph |
| `maps.json`, `maps/MapNNNN.json` | Map index and full decoded maps, tile arrays, events, movement routes, and pages |
| `map_tree.json` | Map hierarchy, editor ordering, start locations, and map properties |
| `transfers.json` | Direct `Teleport` commands and destination map IDs, with their event contexts |
| `database.json` | Complete decoded stored database fields: actors, items, enemies, troops, animation records, terms, system settings, etc. |
| `skills.json`, `items.json`, `enemies.json` | Convenient database subsets |
| `switches.json`, `variables.json`, `commonevents.json`, `troops.json`, `chipsets.json`, `battleranimations.json` | Named records and event/asset definitions |
| `assets.json` | Game media paths, categories, sizes, SHA-256 hashes; ownership left unreviewed |
| `legacy_asset_index.json` | Case-insensitive basename index of current `/mnt/hdd/assets` files, retaining all candidates |
| `legacy_reference_resolution.json` | Historical manifest/document references matched by basename, explicitly unresolved or ambiguous where needed |
| `sources.json`, `summary.json`, `speakers.json` | Input hashes, counts/diagnostics, and literal speaker-label frequencies |

Game media references are relative to `game_root` in `summary.json`. The files
remain in the source project, rather than duplicating hundreds of assets.
`characters.json` uses database record IDs (not JSON array offsets). Charset and
faceset indices are zero-based, as stored by RPG Maker. A character's battle poses
and pose-specific sheet indices are in `battleranimations.json`. Names do not
establish identity across different comic eras.

## Binary decoding method

This is a structural LCF decoder, not `strings`, OCR, or an LLM transcription.
It does not execute events and does not infer a playthrough.

The implementation uses EasyRPG/liblcf's published schema and reader algorithms,
pinned to commit `6854310c3432e553fd4ae672ce861899c80c3bd0`.
The small CSV schemas and MIT license are vendored in `scripts/vendor/liblcf/`,
so repeated extraction needs no network, C++ compiler, or installed EasyRPG.
We did not run the upstream `lcf2xml` executable or compare against its output.

Primary upstream references:

- [Field schema](https://github.com/EasyRPG/liblcf/blob/6854310c3432e553fd4ae672ce861899c80c3bd0/generator/csv/fields.csv)
- [LCF integer and array reader](https://github.com/EasyRPG/liblcf/blob/6854310c3432e553fd4ae672ce861899c80c3bd0/src/reader_lcf.cpp)
- [Event-command reader](https://github.com/EasyRPG/liblcf/blob/6854310c3432e553fd4ae672ce861899c80c3bd0/src/ldb_eventcommand.cpp)
- [Map-tree reader](https://github.com/EasyRPG/liblcf/blob/6854310c3432e553fd4ae672ce861899c80c3bd0/src/lmt_treemap.cpp)

Steps:

1. Validate the `LcfDataBase`, `LcfMapTree`, and `LcfMapUnit` headers.
2. Read compressed big-endian 7-bit integers and length-delimited field chunks.
   Parse nested record arrays by count and original record ID.
3. Resolve field types/names from the pinned schema. Decode packed numeric arrays
   as little-endian; decode flag bitmasks as little-endian bytes. Stat curves are
   six consecutive arrays; equipment contains five signed 16-bit item IDs.
4. Decode command code, indentation, text length/text, and signed integer
   parameters. Preserve order and offsets within each command stream. A zero
   code terminates the stream; only zero padding may follow it. Opcode 10 (`END`)
   is retained as an actual command.
5. Use Windows-1252 for the English project's legacy strings. Preserve control
   codes verbatim. Undefined bytes survive via Python surrogate escapes and JSON
   Unicode escapes; no bytes are silently replaced with replacement characters.
   `--encoding` permits another explicit encoding for another corpus.
6. Export map pages, common events, and battle-troop pages. Each derived command
   includes a stable `source_id` composed of filename, map/common-event/troop ID,
   event ID, page ID, and zero-based command index. Empty components are deliberate.
7. Group contiguous `ShowMessage_2` commands after `ShowMessage` within the same
   stream. Keep choices separately. A prefix such as `Funny Guy:` becomes an
   `explicit_speaker_label`, but `speaker` remains null: literal labels can also
   be narration, and face changes are not reliable identity evidence by themselves.
8. Link actor graphics by case-insensitive stem within the correct game asset
   category. Report all candidates; never silently pick among collisions.
9. Hash source LCF files and game media. Index current legacy paths without hashing
   the entire mixed-media archive. Retain historical paths for provenance.

Only fields actually stored in LCF are emitted in the full decoded records.
Omitted fields are not zeros: RPG Maker uses defaults. The vendored schema contains
those defaults, sometimes `2k|2k3` alternatives. The character convenience view
explicitly applies defaults for graphic indices and battler-animation ID. Consumers
building a runtime must implement the remaining defaults and runtime semantics.
Page condition `flags` determines which condition fields are active; merely seeing
an `actor_id` or `switch_id` does not mean the page checks it. Flag order is in the
vendored `flags.csv`, beginning at bit 0.

Unknown field payloads and unsupported types are retained as base64 objects with
their type/tag. In this corpus the only opaque fields are three empty `EmptyBlock`
markers. There are no unknown command opcodes. These JSON files are a content
export, not a byte-for-byte round-trip serialization format.

## Validation and limits

Nine automated tests cover integer boundaries and negative command parameters,
truncated input rejection, command sentinels, Windows-1252 text, unknown-field
retention, packed values, source hashes, map coverage, message-line conservation,
unique command source IDs, character asset resolution, and direct transfer targets.
Extraction itself checks field consumption, duplicate known fields, file boundaries,
and tile counts against map dimensions. All checks pass for this corpus. A second
extraction into a fresh directory reproduced all 341 output files byte-for-byte.

This does not prove behavioral equivalence with RPG_RT or EasyRPG. Nested branches,
page priority, common-event calls, event-driven actor renaming/graphics, dynamic
transfers, and switch/variable values require interpretation or runtime tracing.
`logic.jsonl` and `transfers.json` retain evidence for that work; they are not a
complete walkthrough or reachability graph. Saves, executable patches, and packed
non-LCF files are outside this extraction. Original image sheets are linked intact;
individual frames have not been cropped or reanimated.

## Flattened legacy assets

The old asset manifest is preserved at
`references/legacy_text/asset_manifest_before_flattening.csv`. The current manifest
was regenerated using `/mnt/hdd/assets`; discovery and thumbnail scripts now use
that root. Matching the historical references found 663 unique basename candidates,
14 ambiguous references, and 1,159 unresolved references. Those counts concern old
references, not proven missing assets. Flattening, renaming, and duplicates prevent
reliable reconstruction from names alone. A unique basename is a candidate, not a
content-hash confirmation. No legacy files were renamed or reorganized.

Older audits and tool-specific examples still describe historical folder layouts.
In particular, `remix_psd.py` and `organize_legacy_dump.py` retain historical paths;
do not run the legacy organizer against the flattened archive. Port individual
workflows using the current index and verified files, not a blind prefix replacement.
