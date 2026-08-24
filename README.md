# Funny Guy — Generative Content

AI-generated art and assets for the Funny Guy universe, built on top of the
existing archive and game rather than replacing them.

## Related repos

- [`../funnyguycomics`](../funnyguycomics) — Hugo archive of the original
  webcomic (2000–2003). Source of characters, lore, and transcripts.
- [`../funnyguyrpg`](../funnyguyrpg) — RPG Maker 2000/2003 game built on the
  Funny Guy IP. Source of sprites, tilesets, and battle assets.
- `/mnt/creative/projects/funnyguy` — legacy asset dump (PSDs, scans, raw
  art). Treated as read-only source material; nothing here copies it into
  git. Point tools at it directly, or mount/symlink locally if that's more
  convenient for a given session.

## Purpose

Generate new Funny Guy art (character portraits, comic panels, RPG-style
sprites/tilesets) that's consistent with the established look: crude,
kid-drawn stick-figure comics on one end, and RPG Maker sprite art on the
other. See `references/style.md` and `references/characters.md` before
generating anything — consistency with 20+ years of canon is the whole
point.

## Layout

- `references/` — character bible and style notes pulled from the archive,
  with pointers to real source files for visual reference.
- `prompts/` — reusable SDXL prompt templates per character/style.
- `workflows/` — ComfyUI workflow JSON exports.
- `scripts/` — pipeline helpers (e.g. batch generation via the ComfyUI API).
- `output/` — generated results. Gitignored; nothing here is source of
  truth.

## Generation stack

Uses the local [ComfyUI](../ComfyUI) install with
[stable-diffusion-xl-base-1.0](../stable-diffusion-xl-base-1.0) as the base
checkpoint. Start ComfyUI, load a workflow from `workflows/`, and feed it a
prompt from `prompts/`.
