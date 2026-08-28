# Master character palette — research

Not built. Noting the approach and what was checked before it's lost.

## What we'd actually be extracting from

Checked colorfulness across the two visual eras before assuming a palette
is even extractable:

- **Original comic style is not a usable color source.** Sampled 8 random
  pages from `funnyguycomics/static/comics/*.png` (338 total) and measured
  the fraction of clearly non-gray pixels: 0–5% on every sample, consistent
  with `style.md`'s note that most originals are black-and-white pencil/pen
  scans. What little non-gray signal there is looks like scan noise/JPEG
  artifacts, not intentional color. Skip this era for palette work
  entirely — there's no character color to recover here.
- **RPG Maker sprite art is the real source.** `funnyguyrpg/CharSet/fg2.png`
  is a palette-mode (`P`) PNG with exactly 21 distinct colors total. Sheets
  like this are small, exact, and already color-quantized by construction
  — no clustering needed, just an exact color-frequency count.
- **The legacy dump has scattered colored material, but it's inconsistent
  and needs filtering.** Spot-checked `Covers/` and `Logos/` (post-reorg):
  some files are colored (`FG Facebook Cover Photo.jpg`, `logo1.png`),
  others are fully grayscale (`fgcomics_logo.png`, `GTH_Logo.gif`), and
  none of it is guaranteed to be character-color-accurate rather than
  incidental web-design color. This is exactly what
  `references/legacy_triage.csv`'s `canon_similarity`/`category` columns
  are for — use them to filter down to genuinely character-relevant, high
  confidence images instead of trusting every colored file that happens to
  be under `Covers/`. Also still need to exclude `stock-rpgmaker`-origin
  files (`DH-*`, `FX-*`), same as `style.md` already calls out — those are
  RPG Maker's bundled resources, not Funny Guy art, and would pollute a
  character palette with someone else's color choices.

## Proposed pipeline

1. **Source selection** — start from `references/asset_manifest.csv` rows
   with `origin` in `{canonical, original-rpg}`, plus
   `references/legacy_triage.csv` rows with high `canon_similarity`
   (e.g. > 0.8) to pull in legacy-dump duplicates/variants of the
   canonical sprites without dragging in stock resources or unrelated
   scans.
2. **Per-character crop** — `CharSet`/`FaceSet`/`Battle` sheets are grids
   holding multiple characters at fixed cell sizes (RPG Maker 2000/2003's
   documented layout, not something to detect — unlike
   `segment_panels.py`, this grid is a known spec). Cast is small (4 named
   characters in `characters.md`), so mapping sheet cell → character name
   is a one-time manual lookup worth recording directly in
   `characters.md` once done, not worth automating.
3. **Extract per-crop palette** — since these sheets are already low-color
   indexed PNGs, use an exact color-frequency histogram (`Image.getcolors`
   / `im.getpalette()` for indexed mode) rather than k-means. Drop RPG
   Maker's transparency key color (convention: the top-left pixel's color
   is the mask color, commonly a specific magenta) before ranking. Keep
   the top N colors by pixel count.
4. **Don't force a single blended color across sheets.** The same
   character rendered in `CharSet` vs. `Battle` vs. `FaceSet` may use
   sheet-specific shading/dithering, so a naive average across sources
   risks producing a muddy hex value that appears in *none* of them.
   Publish each sheet's palette under the character's heading instead of
   collapsing them, and let a human pick the canonical value if a single
   swatch is needed later.
5. **Output** — `references/character_palette.md` (swatches + hex + which
   sheet each came from) or a `character_palette.csv` if something
   downstream wants to parse it. Include rendered swatch thumbnails so a
   human can eyeball it before trusting it in a prompt.

## Why this matters for generation

Right now `prompts/funny_guy.yaml` only has text style descriptors (see
`style.md`'s "generic style descriptors" note, since SDXL wasn't trained
on this IP). A verified per-character hex palette could feed in two ways:
as explicit color hints in the prompt text, or — likely stronger — as a
post-generation palette-matching/quantization step on
`generate_sdxl_img2img.py` output, remapping toward the canonical swatch
set rather than hoping the prompt text alone holds color consistent
across a batch. Not attempted yet; worth prototyping once the palette
itself exists.

## Tooling note

Checked the ComfyUI venv (`/home/adamyoung/src/ComfyUI/.venv`): has numpy,
PIL, and scipy, but no `sklearn` or `cv2`. That's fine for the pipeline
above — PIL's exact color counting is sufficient for indexed sprite
sheets and is more correct than clustering for this source material
anyway. k-means/clustering would only matter for continuous-tone color
sources (photos, painted digital art), and the two candidates for that
(scanned comics, legacy-dump covers) turned out to be too grayscale or
too inconsistent respectively to lean on — see above. Revisit only if a
better continuous-tone color source turns up.
