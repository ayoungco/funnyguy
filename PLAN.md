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
- [ ] 8. (in progress; CDX API 429s, `archive/wayback/cdx_inventory.py` retries with long backoff) Wayback: CDX inventory -> `archive/wayback/manifest.csv`; fetch captures of fgcomics.com/funnyguycomics.com; per-era route manifests
- [ ] 9. Deploy from this repo: repoint infra `static_site` to `repo/funnyguycomics`; apex/funnyguycomics.com -> www 301
- [ ] 10. Archive old GitHub repos (`fgcomics`, and others after verifying) with pointer README
- [ ] 11. Update README "Related repos" and portfolio docs in `../ayoung.co`

## Open questions
- phpBB: one install with per-era skins (recommended) vs. one board per era
- Whether `fgcomics-www` (remote `adambuilds/www.fgcomics.com`) is yours; do not import until confirmed
