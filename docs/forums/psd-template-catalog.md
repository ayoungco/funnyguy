# Forum/site PSDs awaiting template conversion

Source: `/mnt/hdd/assets/funnyguy/FGComics - Website.zip` (33 PSDs; 867 PSDs on the drive overall).
Previews were rendered with ImageMagick (flattened composite only). Unpack to scratch, never in-repo.

## Real layouts (convert to phpBB 3.3 styles)
| PSD (in zip) | Size | What it is |
|---|---|---|
| `FGComics II/dispersion_condensed.psd` | 800x640 | "FGComics II" chrome design: star logo, rainbow nav (BLOG/STUFF/FORUM/ABOUT/SUBMIT). Best candidate for a phpBB3 theme. |
| `psd/header_preliminary.psd` | 800x205 | Notebook-paper FGComics header with hand-lettered nav (Home/The Comix/Other Stuff/About). 2005-06 look. |
| `psd/new_design.psd` | 800x600 | Flattened composite is blank; needs layer-by-layer extraction. |
| `psd/nav.psd`, `img/nav.psd` | ~650x100 | Nav strips. |
| `psd/Aura_1_aayoung.psd`, `_OFFLINE/psd/roxas.psd` | 800x600 | Unidentified; review layers. |

## Forum UI parts
`_OFFLINE/psd/`: New Concept Buttons / Post Buttons / Small Buttons, forum_rank, forum admin logo,
table_header, no_game_picture, new!, signs. Reusable as phpBB3 button/rank images.

## Do not reuse
`_OFFLINE/psd/IPB/*` (GDK1.2_PSP, tile_cat, nav, stats, flashers, anim) is Invision's stock Graphics
Development Kit, licensed for use only inside Invision Board. Skip for the phpBB resurrection.

## Plan
1. Extract layers with `psd-tools` (needs `pip install` into a venv) for the two chrome designs.
2. Slice to CSS/images; build a phpBB 3.3 style (`prosilver` child) per era.
3. Per-era skins on one install: 2005 phpBB2-look (notebook header), 2006 dispersion chrome.
4. Strip PMs/emails/IPs/hashes before any import (see `repo/funnyguycomics/docs/research/forum-archive.md`).
