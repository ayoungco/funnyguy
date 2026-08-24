# Tileset restyling — stub

Not built. Noting the approach before it's lost.

Idea: restyle `../../funnyguyrpg/ChipSet/*.png` (and other tile atlases —
`Backdrop/`, `Panorama/`) through the hand-drawn art direction, the same
way `scripts/generate_sdxl_img2img.py` restyled character art, while
staying a functional drop-in replacement for RPG Maker.

This is a harder case than character portraits or comic panels — a
chipset atlas encodes many small tiles at exact fixed pixel positions,
and autotile edge variants have to stay pixel-aligned with their
neighbors so the engine can still tile them seamlessly. Running a whole
chipset image through SDXL img2img the way we did the character panel
would blur/shift things just enough to break that alignment (1024px SDXL
working resolution vs. 16-32px native tiles doesn't help either).

Proposed approach:

1. Split the chipset into individual tiles along RPG Maker's known, fixed
   grid — trivial compared to `scripts/segment_panels.py`'s comic-panel
   detection, since this layout is a documented spec, not something that
   needs guessing.
2. img2img per tile or small related tile-group (e.g. one autotile
   family) at low strength — maybe 0.2-0.35, well below the 0.6 that
   worked for characters — upscaling into SDXL's working resolution and
   back down carefully to preserve hard pixel edges.
3. Reassemble at the original atlas positions.

Tradeoff to test before batching: low strength preserves tileability but
limits how much visual "flair" comes through (texture/linework restyle,
not a redesign); pushing strength higher risks visibly glitching edge
transitions in-game (mismatched grass/water/path borders). Prototype on
one small tile family first and check it actually still tiles, before
committing to a full chipset or a batch run.
