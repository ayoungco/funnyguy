# Funny Guy Comics

This repo now holds a Hugo-powered static archive of the original Funny Guy Comics site. The historic PHP implementation (and its MySQL dump) are still present for reference, but new work should happen through Hugo.

## Getting started

- `hugo server` – live preview at <http://localhost:1313> using the Hugo source in this repo.
- `hugo --minify -d <dir>` – builds the site into `<dir>`. Build outside the repo (e.g. `/tmp/fgb`); `public/` is a stale, gitignored 2025 build of the old PHP site and must never be deployed.
- Static assets live under `static/` (comics, images, fonts, downloads). All comic markdown lives in `content/comics/` with metadata generated from `fgcomics.sql`.

## Data provenance

`source/fgcomics.sql` (kept out of `data/`, which Hugo parses) remains the source of truth for comic metadata. The seed scripts imported it into two Hugo data files:

- `data/comics.json` – each page of every comic with dimensions and transcripts.
- `data/collections.json` – the original "book" groupings displayed on the archive page.

If the SQL dump changes, regenerate those files and re-run `hugo` to rebuild the output.

## Routes

Canonical host is `https://www.fgcomics.com/`; `funnyguycomics.com` (and the apex) 301 to it at the host.
The 2000s PHP URLs still resolve: `/<N>/` and `/<N>/<page>/` are Hugo aliases generated into each comic's
front matter by `scripts/gen-aliases.py` (re-run it after changing `data/comics.json`), `/rss.xml` is the feed,
and `/comics/<NNN>_<name>.png` image URLs must be rewritten to `.webp` by the web server (see infra's `static_site` role).

## Legacy PHP notes

The previous PHP site is untouched inside `public/`. The existing TODO list for that stack is preserved below for posterity:

1. Enable PHP extensions in docker-compose.yml, they aren't working right now.
2. Change MySQL connection to SQLite, it is a static db and doesn't need to be on a server.
3. Implement CI/CD on GitHub, or install GitLab locally in my home environment.
