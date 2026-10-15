# Forums

Resurrection of the 2005 (phpBB 2.0.11) and 2006 (IPB 2.x) FGComics forums as a phpBB 3.3 install.
See `PLAN.md`, `docs/forums/psd-template-catalog.md`.

- `styles/dispersion/` — chrome from `FGComics II/dispersion_condensed.psd` (layers extracted with psd-tools). `preview.html` is a static mock; next is wrapping it as a phpBB 3.3 prosilver child style.
- Do not commit forum DB dumps (PMs, emails, IPs, hashes). Normalizer: `repo/funnyguycomics/docs/research/forum_normalize.py`.
