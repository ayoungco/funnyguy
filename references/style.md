# Style notes

Two distinct visual targets exist in canon. Pick one per generation batch.

## 1. Original comic style

- Crude, kid-drawn stick figures — deliberately unpolished, not "bad
  digital art," genuinely hand-drawn-on-notebook-paper energy.
- Six-panel grid layout in the earliest strips.
- Dot eyes, minimal linework, flat coloring where colored at all (many
  originals are black-and-white pencil/pen).
- Source: `../../funnyguycomics/static/comics/*.png` — browse a range of
  numbers, not just #001, since the style drifted as the creator aged
  through the run.

## 2. RPG Maker sprite style

- 16-bit era JRPG sprite conventions (RPG Maker 2000/2003 default
  resolution and proportions — small chibi-esque overworld sprites,
  higher-detail battle/face art).
- Source: `../../funnyguyrpg/CharSet/`, `../../funnyguyrpg/FaceSet/`,
  `../../funnyguyrpg/Battle/`, `../../funnyguyrpg/BattleCharSet/`.
- Many assets in those folders (the `DH-*.png`, `FX-*.png` files etc.) are
  *stock* RPG Maker resources, not Funny Guy-original art — don't use them
  as style reference for the cast itself, only for environment/tileset
  consistency if generating game assets.

## Doing SDXL generation against either style

SDXL wasn't trained on this specific IP, so prompts need to lean on
generic style descriptors (e.g. "crude stick-figure cartoon, notebook
doodle style, dot eyes" or "16-bit JRPG sprite, pixel art, chibi
proportions") rather than named-character shorthand, plus img2img/ControlNet
against a real reference file from above when consistency matters more
than novelty. See `../prompts/` for starting templates.
