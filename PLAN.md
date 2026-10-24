# Consolidation Plan

Goal: this repo is the authority for the whole Funny Guy series. Everything lives here, artifacts deploy from here.
Decision log: `docs/progress.md` (2026-10-14). Detail docs: `docs/forums/`, `repo/funnyguycomics/docs/research/forum-archive.md`.

## Layout
- `repo/<name>/` — imported repos, whole, history preserved (landing zone; promote into a cleaner structure later)
  - `funnyguycomics` (Hugo site), `funnyguyrpg` (game), `fgcomics2006` (2006 site + phpBB2), `fgoac` (2004 site)
- `docs/`, `data/`, `references/`, `scripts/` — canon, extraction, tooling (the old "factory")
- `archive/wayback/` — CDX manifests + fetch scripts (raw captures stay out of git)
- `repo/forums/` — resurrected phpBB 3.3 styles + import tooling

## Steps
- [x] 1. Drop "publish-only" rule for comics
- [x] 2. Import fgoac, fgcomics2006, funnyguyrpg, funnyguycomics into `repo/` with history
- [x] 3. Catalog forum/site PSDs (`docs/forums/psd-template-catalog.md`)
- [x] 4. Reconcile local `~/src/fgcomics` (325 uncommitted edits) against `repo/funnyguycomics`; then retire clones
- [x] 5. Extract PSD layers (psd-tools venv): `dispersion_condensed`, `header_preliminary`, `new_design`
- [ ] 6. Build phpBB 3.3 styles per era — dispersion chrome prototyped in `repo/forums/styles/dispersion/`; 2005 notebook header + phpBB3 wrapping remain
- [ ] 7. Forum data: normalize dumps (done in comics repo), strip PMs/emails/IPs/hashes, import via phpBB convertor (2005) + custom (IPB 2006)
- [ ] 8. (2026-10-22: first pass DONE — 388 captures inventoried and all fetched to `archive/wayback/raw/`, but thin: 313 are from 2022, only 11 from 2005, 1 from 2006; HTML-only, few assets. See steps 12-13 for the deeper pass) Wayback: CDX inventory -> `archive/wayback/manifest.csv`; fetch captures of fgcomics.com/funnyguycomics.com; per-era route manifests
- [ ] 9. Deploy from this repo: repoint infra `static_site` to `repo/funnyguycomics`; apex/funnyguycomics.com -> www 301
- [ ] 10. Archive old GitHub repos (`fgcomics`, and others after verifying) with pointer README
- [ ] 11. Update README "Related repos" and portfolio docs in `../ayoung.co`

## Open questions
- phpBB: one install with per-era skins (recommended) vs. one board per era
- Whether `fgcomics-www` (remote `adambuilds/www.fgcomics.com`) is yours; do not import until confirmed

## Added 2026-10-22: fgcomics.com as its own archive
Goal: fgcomics.com surfaces all historic content, every era, with the abandoned design comps as switchable themes.
- [ ] 12. Wayback deepening: re-run CDX with sub-resources (img/css/js/swf), `matchType=prefix` per subdomain (forums., old fgoac domain, www/non-www, :80 variants), no `collapse=digest` for 2004-2008, include 3xx; add Wayback `if_`/`im_` asset capture for each page; confirm fgoac (2004) hosts; check archive.today / Common Crawl / Internet Archive item collections as fallbacks
- [ ] 13. Era coverage report: per-year counts of captured pages vs. local sources (`repo/fgoac`, `repo/fgcomics2006`, `repo/funnyguycomics`, forum dumps); list gaps and cross-link to local originals
- [ ] 14. PSD comp triage: flatten every comp-like PSD (zip has 51; drive has 527, catalog claims 867 — reconcile) to preview PNGs; classify shipped / prototype / never-made-it; extend `docs/forums/psd-template-catalog.md`
- [ ] 15. Comp -> mockup templates: self-contained `theme-<name>/` HTML+CSS+sliced images per comp, shared sample content; start with `dispersion_condensed` (prototype exists in `repo/forums/styles/dispersion/`), `header_preliminary`, `new_design` (blank flatten, needs layer extraction), `nav`, `Aura_1`, `roxas`
- [ ] 16. Theme gallery on fgcomics.com with switcher + random button (stylesheet swap, localStorage; precedent: 2005 `skinchanger.php`); also surface archived eras as themes/timelines
- [ ] 17. Era browser: `/archive/<year-or-era>/` pages serving archived bytes with a Wayback-style timeline; link from ayoung.co and young.is


### Decisions and progress 2026-10-22
- Forum reclaim: build a **scrubbed working copy** (IPs and password hashes removed, PMs excluded). Keep emails only in the private working copy so members can reclaim accounts through verified email reset. Make the board look like stock modern phpBB 3.3 (prosilver); era skins stay optional extras. Nothing with emails goes in git.
- Wayback step 12 started: `archive/wayback/cdx_deep.py` running in background (log `cdx_deep.log`, cache `cdx_cache/`, resumable, output `manifest_deep.csv`).
- Step 14 started: contact sheet for the zip done (see catalog triage update). Only 3 real comps in the zip; next, scan the rest of the drive.
- Step 14 sweep 2 done (2026-10-22): drive top level yields FGComics masthead, notebook/logo sources, plus two complete non-FGComics school-era site comps (Evil Scientist, Revenge/Hamlet). Remaining: `header.fla`, `.ai`, and JPG/PNG mockups. Details in the catalog.

### 2026-10-23 reorientation
- Drive reorganized: `/mnt/hdd/assets/*` is now `/mnt/hdd/*`. Fix path references in `docs/asset-import.md` and the catalog when next touched.
- Wayback deep inventory finished: `archive/wayback/manifest_deep.csv`, 5,667 captures (first pass: 388). `fgoac.com` returned 0 (503s at the end; retry), `funnyguy.com` result not yet checked. Next: era coverage report (step 13), then fetch raw bytes with `fetch_raw.py manifest_deep.csv`.
- Step 14 done for PSDs: found the New Concept (x4) and OUTSIDEBOX (x2) comps; layers extracted; `new_design` was a blank canvas. Step 15 can start; first template should be New Concept (four colorways).
- New sources spotted on the drive, not yet examined: `/mnt/hdd/funnyguy/IPOWER-Collections.zip`, `IPOWER-The Funny Guy Comix.sql.zip`, `The Funny Guy Comix.sql.zip`, `Comics Database - Pre-December 2015.7z`, `fgcomics-data-model.ods` (likely 2006-2015 site data and DB history; relevant to steps 7 and 13).
- **Correction (2026-10-23):** `funnyguy.com` is NOT ours (a stranger's comedian site you only wanted the domain for). The 5,096 captures from it were never fetched and must never be. Use `archive/wayback/manifest_own.csv` (fgcomics.com, funnyguycomics.com and subdomains only, e.g. `likes.fgcomics.com`) for all fetching; `manifest_deep.csv` includes the stranger's rows and is kept only as raw CDX output. `cdx_deep.py` no longer queries funnyguy.com. The pre-2005 history is therefore NOT in Wayback under our domains; the 2004 site (`fgoac`) domain is still unknown.
- **2004 site location (2026-10-23):** `www.freehostz.com/fgoac/` (free host; confirmed by links in `repo/fgoac`). freehostz.com hosts strangers' sites, so only the `/fgoac/` path may be queried or fetched, never the whole domain (`cdx_deep.py` PREFIXES enforces this; `manifest_own.csv` filter must also admit only freehostz.com/fgoac paths). The Internet Archive was offline ("Temporarily Offline") on 2026-10-23, so these CDX queries have not yet run; rerun `cdx_deep.py` when it is back (cache makes it resumable), then rebuild `manifest_own.csv`.
- **Step 15/16 started (2026-10-23):** `repo/fgcomics-themes/` is a static theme gallery with switcher + random button. Done: New Concept sky/blue/green (one shared chrome, three colorways, verified in headless Chrome). Remaining themes: New Concept 4 navy, OUTSIDEBOX, dispersion, notebook, masthead, plus the non-FGComics comps. Not yet deployed anywhere.
- **Forum reclaim DB built (2026-10-23):** `forum_private.sqlite` on the HDD (private; emails + PMs kept, hashes/IPs/logs dropped, leak-checked). See `docs/forums/reclaim-database.md`. Next: phpBB 3.3 install (prosilver), import posts/members, reclaim flow keyed on email, link renamed accounts across eras.
- **phpBB dev stack running (2026-10-23):** `repo/forums/dev/dev.sh`, phpBB 3.3.19 stock prosilver on localhost:8081, empty board. Next: importer (members -> posts, phpBB2 BBCode + IPB HTML -> phpBB3 storage), mailpit for reclaim emails.
- **Forum import done (2026-10-23):** 8,634 posts / 405 topics / 40 accounts in the local phpBB 3.3.19 dev board (stock prosilver). Notes and theme evidence in `docs/forums/import-notes.md`. Remaining: Elemental style (blue/green/red) for phpBB3, mailpit + reclaim test, polls, avatars/ranks, search index, PM policy at reclaim, the 2008 phpBB3-era forum (Wayback only, no dump).

## Elemental default (2026-10-24)
phpBB3 child style `repo/forums/styles/elemental` installed as board default via `dev.sh style` (`import/install_style.php`). Todo: green/red variants, header logo, per-post/profile polish.
