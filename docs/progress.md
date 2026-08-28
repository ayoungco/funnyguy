# Progress log

Running narrative of work on this project. Newest entries at the top.

## 2026-08-28 — Slideshow videos regenerated; fixed a stale-fallback bug in make_slideshow.py

Re-ran `make_slideshow.py` over the full set after the panel segmentation
fix above changed panel counts for a chunk of the corpus.

Before batching 250 comics through it, checked the nvenc/CPU fallback
change that had been sitting uncommitted in this file (see prior
entries): its comment claimed this ffmpeg build has no software libx264
encoder, hardware-only H.264, and fell back to `libvpx-vp9` instead. That
premise is false on this machine right now — `ffmpeg -encoders` lists
`libx264` and a direct smoke-test encode with it works fine. The
untested version would have silently muxed VP9 into a `.mp4` container
for any comic where nvenc failed twice. Reverted the fallback codec to
`libx264` (kept the nvenc-retry-once addition, which is a reasonable,
independent fix). nvenc itself worked on every comic in this run — GPU
was idle — so the fallback path never actually triggered.

Result: 250 videos regenerated in `output/videos/` (spot-checked several
with `ffprobe` — valid h264, sane durations). Also found and cleaned up
33 stale video files left over from before the segmentation fix — these
were for comics that used to "succeed" (into wrong, oversized merged
panels) and now correctly fail segmentation, so their old videos were
orphaned but not automatically removed. `make_slideshow.py` doesn't
touch `output/videos/<slug>.mp4` at all when segmentation has no panels
for that slug, so those go stale silently on a re-run unless someone
checks for the mismatch — worth keeping in mind for the next corpus-wide
re-run too, this isn't fixed at the script level, just cleaned up by hand
this time.

## 2026-08-28 — Panel segmentation: fixed the fixed-black-level assumption

Follow-up to the segmentation-status entry directly below this one. The
hypothesis raised for the 26%-fail/58%-low-confidence numbers: these are
pencil drawings, not inked linework, so a page's "black" isn't one fixed
value — checked it empirically before changing anything.

Confirmed: `segment_panels.py` was thresholding on a single fixed
darkness cutoff (`arr < 128`) to find border lines. Per-page percentile
stats (p1/p99) showed almost every page already has near-black content
somewhere (heavy shading, bold outline) even when the specific
panel-divider lines are a much lighter pencil gray — so a global
per-page contrast stretch made no difference (the extremes were already
near 0/255) while the fixed cutoff still missed the actual divider
lines. What worked: outer-looping over progressively lighter darkness
cutoffs (128 → 160 → 190) before giving up, the same pattern already
used for the row/column fraction threshold.

That alone over-rescued: some pages got "successfully" split into 60-80
postage-stamp fragments (internal shading misread as grid lines) instead
of real panels. Added a size guard — reject any split where a panel's
shorter side is under 5% of the page's matching dimension — which
forces those cases back to a lighter cutoff or an honest failure instead
of silently returning garbage.

Net result re-running all 338 comics: **the aggregate counts are
unchanged** (55 ok / 195 low-confidence / 88 failed) — same numbers as
before, but not the same *set*. 33 comics that previously failed
outright now segment cleanly (verified sane panel counts, 2-20 range).
33 different comics that were previously counted "successful" now fail
instead — checked two of these by eye
(`056_bomb_bash`, `156_spaghetti_confetti`) and both are dense ~30-40
panel pages that the old fixed-cutoff code had silently mangled into 5-6
oversized merged blobs, not legitimate small-panel layouts. So this
isn't a wash: it's roughly 33 real fixes plus 33 silent-wrong-answers
turned into honest failures the manifest now surfaces for a manual look,
same "still a rough pipeline" philosophy already in the script's
docstring. The 88 still failing after this need a different fix, not
more of this one.

Also fixed a real bug hit while re-running: `segment_panels.py` never
cleared a comic's output directory before writing, so a comic whose
panel count changed between runs kept stale extra crops from the
previous run alongside the new ones (63 directories had this after the
above change, before the fix). Now clears `panel_*.png` before writing,
whether the new run succeeds or fails.

`output/videos/` (from `make_slideshow.py`) is now stale for every
comic whose panel set changed — not re-rendered as part of this, since
that's a separate, slower batch step and `make_slideshow.py` still has
its own pending untested nvenc-fallback change sitting in the working
tree (see entry below). Re-run `make_slideshow.py` over the full set
when that's wanted.

## 2026-08-28 — Legacy dump reorganized; panel segmentation status corrected; more uncatalogued material flagged

Reorganized the ~690 loose files that used to sit at the top level of
`/mnt/creative/projects/funnyguy` into folders (`scripts/organize_legacy_dump.py`,
dry-run by default, undo manifest on `--execute`) and refreshed
`references/asset_manifest.csv` against the new layout. See README/script
docstring for how it classifies.

**More material in the legacy dump than the manifest shows.** By design
`discover_assets.py` only catalogs image/design extensions and prunes
`Trash`, `Website Backups`, and `Playthrough` entirely at the top level —
worth calling out explicitly since it's easy to assume the manifest is a
complete inventory:

- `Playthrough/` (9.2 GB, pruned) — actual gameplay capture: 7 `.mkv` +
  3 `.mp4` recordings, a `.prproj` Premiere project, raw audio. Potential
  source material for something (trailer? reference for pacing/VO?) but
  currently invisible to every tool in `scripts/`.
- `Website Backups/` (0.7 GB, pruned) — old site archives (`.zip`/`.7z`)
  and a server `.conf`. Historical-only, but not nothing.
- `Trash/` (pruned) — 2 stray `.psd`, genuinely trash.
- 682 non-image files inside the folders that *are* cataloged, invisible
  because `discover_assets.py` filters to `ASSET_EXTS` by default (rerun
  with `--all-files` to see them). The bulk (600) is under `RPG_RT/` —
  `.lmu` map files, `.wav`/`.mid` audio — which is the actual RPG Maker
  project/engine data, not art, so it's a different kind of asset than
  anything currently modeled in the manifest's `origin` scheme. The rest
  (`Documents/`, `Guide/`, etc.) is mostly design docs/text.

None of this blocks current work, but neither `references/asset_manifest.csv`
nor `references/legacy_triage.csv` should be read as "everything in the
dump" — treat them as "the image/design assets we've looked at so far."

**Panel segmentation: actual corpus results are rougher than the
2026-08-23 entry below implies.** That entry validated
`segment_panels.py` against three hand-picked pages (a clean 6-panel, a
clean 42-panel, one irregular page) and called the geometric
border-detection approach good. The full run since then
(`output/panels/segmentation_manifest.csv`, 338 comics) tells a less rosy
story:

- 55 comics (16%) segmented at the top confidence threshold (0.85)
- 195 comics (58%) only succeeded at the lowest threshold (0.4) —
  `ok_low_confidence`, meaning the border-detection had to get
  permissive enough that panel boxes are worth spot-checking
- 88 comics (26%) failed outright — no usable grid lines found at any
  threshold, no panels produced

So a quarter of the archive isn't segmented at all, and more than half of
what *did* segment is flagged low-confidence rather than clean. This
wasn't visible from the 3-page spot check. `make_slideshow.py` has since
run over the successful 250 and produced videos in `output/videos/`
(matches 55+195), so the pipeline is otherwise working end-to-end for
whatever did segment — the gap is specifically in the border-detection
step for the harder ~26%+58% of pages. Worth a closer look (e.g. logging
example failures, or trying a lower fourth threshold / different dark-pixel
cutoff for that subset) before leaning on this corpus for anything beyond
rough slideshow output. Not investigated further this session.

`scripts/make_slideshow.py` also has an uncommitted, untested fix in the
working tree (nvenc retry-once-then-fall-back-to-libvpx-vp9, since this
ffmpeg build has no software libx264) — pre-existing before this session,
still pending.

## 2026-08-23 — First real generation: SDXL pipeline, img2img over txt2img

Built the actual generation pipeline the project is named for (nothing
had been generated before now). Decided to drive SDXL via the `diffusers`
library directly rather than a ComfyUI server: the local checkpoint is
checked out in native diffusers layout, and converting it to the
single-file format ComfyUI's loader expects would be extra fragile
machinery just to run a batch script. `workflows/` stays for an
interactive ComfyUI session later if that's ever wanted.

Confirmed the risk `references/style.md` already called out: plain
text-to-image drifted off-model (a polished coloring-book illustration
instead of a crude stick figure; a grid of generic anime sprites instead
of one consistent character). Fixed by switching to img2img against real
reference material instead of generating from noise — and specifically
against a single *segmented panel* rather than the whole comic page or
the raw reference file, since a whole 6-panel page doesn't match what a
character-portrait prompt is asking for. That fix produced genuinely
on-model results in both styles on the first real test. Nice unplanned
payoff from the panel segmentation work above — it turned out to be the
right source-prep step for generation too, not just for slideshows.

Also noted (not built): the same img2img approach won't work as-is on
`funnyguyrpg/ChipSet/*.png` tile atlases — those need pixel-exact
alignment between adjacent tiles for RPG Maker's autotiling to still
work, which a whole-image diffusion pass would break. See
`references/tileset_restyling.md` for the per-tile, low-strength approach
proposed instead.

## 2026-08-23 — Panel segmentation + slideshow videos

Decided the comic archive is worth turning into rough animated shorts:
segment each page into its individual panels, then assemble a
slideshow-style video per issue (panel held on screen, gentle zoom,
crossfade to the next). Chose geometric panel-border detection over a
fixed grid assumption, since panel layout drifts across 20+ years of
hand-drawn pages — a fixed grid would misalign on plenty of issues.
Validated on a clean 6-panel page and a 42-panel page (both segmented
perfectly) and a genuinely irregular 5-column/variable-height page (still
usable, but the ornate logo box in that one fragments into a few junk
crops — a known, accepted limitation rather than something worth
special-casing).

## 2026-08-23 — Cataloging source material before generating anything

Decided nothing gets generated until there's a full picture of what
already exists — otherwise prompt/reference work risks treating RPG
Maker's stock resource sheets as if they were Funny Guy art, or missing
usable material buried in the legacy dump. `scripts/discover_assets.py`
now catalogs all three source locations into `references/asset_manifest.csv`,
tagged by origin (original comic art / original RPG art / RPG Maker stock
/ unsorted legacy / hand-picked canonical). See README for how it works.

Decided generated content belongs with the source material it's derived
from — on the NAS at `/mnt/creative/projects/funnyguy/generated/` —
rather than in git or in a new location split off from the existing
archive. The repo stays config-and-pipeline only. (Currently blocked by
a NAS mount permission issue — see README.)

## 2026-08-23 — Project scaffolded

Created the repo to hold AI-generated Funny Guy content, separate from the
two existing repos it draws on:

- [`funnyguycomics`](../../funnyguycomics) — the original 2000–2003
  stick-figure webcomic archive (Hugo site).
- [`funnyguyrpg`](../../funnyguyrpg) — the RPG Maker game built on the same
  IP.
- `/mnt/creative/projects/funnyguy` — a legacy raw-asset dump (PSDs, scans)
  kept out of git and referenced by path only.

Decided the initial focus is AI art/asset generation (character portraits,
comic panels, RPG-style sprites) using the local ComfyUI install with
`stable-diffusion-xl-base-1.0` as the base checkpoint — both already
present under `../../`.

Laid out the repo:

- `references/characters.md` — character bible seeded from
  `funnyguycomics/content/history.md` and comic transcripts: Funny Guy,
  Koven, Repair Man, Serious Man, plus notes on the three eras of existing
  art (original comic style, RPG Maker sprites, the raw legacy dump).
- `references/style.md` — the two visual styles worth targeting (crude
  stick-figure comic vs. 16-bit RPG sprite) and why they shouldn't be
  blended within a single generation batch.
- `prompts/funny_guy.yaml` — first SDXL prompt templates for the title
  character, one per style, each pointing at a real reference image.
- `workflows/`, `scripts/`, `output/` — scaffolded but empty; ComfyUI
  workflow exports, batch-generation code, and generated results
  respectively go here as the pipeline gets built.

Nothing generated yet — this entry is scaffold-only.
