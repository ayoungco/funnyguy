# Progress log

Running narrative of work on this project. Newest entries at the top.

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
