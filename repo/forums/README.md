# Forums

Resurrection of the 2005 (phpBB 2.0.11) and 2006 (IPB 2.x) FGComics forums as a phpBB 3.3 install.
See `PLAN.md`, `docs/forums/psd-template-catalog.md`.

- `styles/dispersion/` — chrome from `FGComics II/dispersion_condensed.psd` (layers extracted with psd-tools). `preview.html` is a static mock; next is wrapping it as a phpBB 3.3 prosilver child style.
- Do not commit forum DB dumps (PMs, emails, IPs, hashes). Normalizer: `repo/funnyguycomics/docs/research/forum_normalize.py`.

## Local dev stack (`dev/`)
`dev/dev.sh up|install|import|down|reset|shell|db`: podman pod with MariaDB 11 + php:8.3-apache serving phpBB 3.3.19 (stock prosilver, checksum-verified download) at http://localhost:8081, bound to localhost only.
- Dev-only credentials: admin / `devadmin-change-me`, DB root `devonly-not-a-secret`. Never reuse for production.
- `dev/phpBB3/` is the unpacked release (gitignored). The installer dir is removed after install, VigLink extension disabled.
- Fresh start: `dev.sh reset && dev.sh up && dev.sh install`. Email is disabled, so reclaim emails need a local mail catcher (mailpit) before testing.
- Private data lives at `/mnt/hdd/funnyguy/forum/forum_private.sqlite` (see `docs/forums/reclaim-database.md`); the dev DB will hold emails and PMs once imported, so keep it local.

### Elemental style (board default)

`styles/elemental/` is a child of prosilver that restores the 2005 board's look (blue/orange, `cellpic` gradients from `fgcomics2006/forums/templates/Elemental`). `dev.sh style` copies it in and makes it the default; prosilver stays installed for users who pick it. Green and red variants are still to do (override the `--el-*` variables and hue-shift the three gradient images).
