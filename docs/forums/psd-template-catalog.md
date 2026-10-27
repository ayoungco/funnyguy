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

## Triage update 2026-10-22 (`scripts/psd_contact_sheet.py`)
The zip holds 33 PSDs (not 51; that count included non-PSD entries). Flattened previews checked by eye:
- `psd/Aura_1_aayoung.psd` (1600x1200): **not FGComics.** A dark red "AAYM" machine-shop site comp, a separate web-design project. Candidate for ayoung.co portfolio, not for the comics themes.
- `_OFFLINE/psd/roxas.psd` (1024x768): Kingdom Hearts wallpaper, not a site comp.
- `_OFFLINE/psd/tkw_logo.psd`: "The Korean War" logo, unrelated project.
- `psd/header_preliminary.psd` (1003x257) + `psd/nav.psd` / `img/nav.psd`: the notebook-paper FGComics design; nav has black and red hover states (Archive / Extra Stuff / News / History).
- `FGComics II/dispersion_condensed.psd` (1500x1200, not 800x640): glossy gray chrome, star logo, rainbow nav, empty white content panel.
- `psd/new_design.psd` (1600x1200): composite blank, still needs layer extraction.
- The other 24 are favicons, buttons, forum rank/table images and Invision stock files.
So the zip yields only **three real FGComics comps** (dispersion, notebook, new_design). Finding more "never made it" comps means searching the rest of the drive's PSDs (527 under `/mnt/hdd/assets`) and other formats (see PLAN step 14).

### Drive top level (`/mnt/hdd/assets/*.psd`, 18 files) — second sweep
Contact sheets saved in `output/psd_triage/` (`zip_sheet.png`, `zip_comps.png`, `toplevel_sheet.png`).
- `masthead.psd` (800x125): **FGComics.com "We're freakin awesome!"** — gray gradient banner with the star logo. Same family as dispersion; likely an earlier or sibling comp.
- `mainlinks.psd` (600x300): FGComics logo lettering exploration (about 14 sketchy/outlined variants) plus prev/next/home arrows. Logo history, not a layout.
- `notebook.psd` (1280x960): photo of a spiral notebook with "AND NOW FOR SOME FUN!", the paper-texture source for the notebook-header era.
- `Evil Scientist Website Template.psd` (1000x1100) + `page1/4/5/6/7ofppt.psd` + `header.psd`/`menued_header.psd`/`page.psd`/`backarrow.psd`: a "Wayne & Adam" site, "The Evil Scientist's Chemical Reactions" (hydrogen peroxide / elephant toothpaste), with a green-on-dark-gray template and a separate purple "REVENGE: The Age-Old Catalog of Violence" (Hamlet analysis, school-project) layout with purple nav. Complete comps with real layouts, not FGComics; strong theme-gallery candidates as "other sites".
- `product links*.psd`, `icons.psd`, `logo.psd` (6000x4500 logo sheet), `strike.psd` (blank): graphics parts, nothing to template.
- Still unreviewed for comps: `funnyguy/comics/` (396 PSDs, comic pages, presumably no comps), `superfamily/`, `koreanwar/` (video titles), and non-PSD formats (`header.fla` Flash, `logo.ai`, `dagger.ai`, JPG/PNG mockups).

### Candidate theme list so far (for PLAN step 15)
FGComics: dispersion (2006), notebook header (2005-06), masthead gray banner, new_design (needs layers).
Other sites by you: Evil Scientist, Revenge/Hamlet, AAYM machine shop (Aura_1).

## Triage update 2026-10-23 — drive reorganized; 190 PSDs now in `/mnt/hdd/graphics/`
**Path change:** `/mnt/hdd/assets/*` moved up to `/mnt/hdd/*` (e.g. `/mnt/hdd/projects/funnyguy/FGComics - Website.zip`, `/mnt/hdd/graphics/*.psd`). Older docs citing `/mnt/hdd/assets` are stale. Full sweep of `/mnt/hdd/graphics` (258 PSDs incl. subfolders): `scripts/psd_contact_sheet.py`; layer export: `scripts/psd_layers.py` (needs `psd-tools` in a venv). Outputs in `output/psd_layers/<name>/` (gitignored): `composite.png`, `layers.json`, per-layer PNGs.

**New real FGComics comps found (all have live layers, extracted):**
| PSD | Size | What it is |
|---|---|---|
| `New Concept.psd` | 700x695 | 2005 "FGComics" glossy-gray chrome, sky-blue banner, copyright footer "Copyright (c) 2005 FGComics.com". 35 layers. |
| `New Concept 2.psd` | 700x695 | Same chrome, **blue** colorway, "FGComics.com" wordmark, Google Ads sidebar box and Upload button. 57 layers. |
| `New Concept 3.psd` | 700x700 | **Green** colorway of the same. 62 layers. |
| `New Concept 4.psd` | 800x800 | Dark navy/indigo variant with footer links "About Us - History - Upload Files - Forums - Email Us". 12 layers. |
| `OUTSIDEBOX.psd` / `OUTSIDEBOX2.psd` | 1500x1115 / 1500x1100 | Purple-blue banner, white rounded content tab, star logo; OUTSIDEBOX2 adds the tagline "We're freakin awesome!!!". Menu shine and alt-menu layers. 11 / 14 layers. |
The matching `New Concept (Small/Post) Buttons.psd` in the zip are dated 2005-11-21 to 2005-12-09, so the New Concept family is **late 2005**, the same weeks the board moved from phpBB to IPB. These look like the comps for the site that followed the notebook header.

**Dropped:** `new_design.psd` is a 2011 blank white canvas with no layers (122 KB), not a comp. Remove it from the "needs extraction" list.
**Also layered and extracted:** `dispersion_condensed` (31 layers), `header_preliminary` (2), `masthead` (8).
**Other drive finds:** `7dzyne-design.psd` (brand logo, not a layout), `ayoung.co`/AYOUNG.CO and AA Young & Co "Fact No." graphics (ayoung.co brand), Flash sources (`header.fla`, `korea2.fla` etc. in `/mnt/hdd/graphics`, `Lucky Animals.fla` in `/mnt/hdd/projects/funnyguy`) still unreviewed.

### Revised FGComics theme list (PLAN step 15)
1. Notebook header (2005-06, hand-lettered)  2. New Concept blue / green / sky / navy (late 2005; one chrome, four colorways = natural switcher demo)  3. OUTSIDEBOX purple tab  4. Dispersion (FGComics II, 2006)  5. Masthead gray banner.
Other-site comps for the gallery: Evil Scientist, Revenge/Hamlet, AAYM.
