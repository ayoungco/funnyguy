# Funny Guy Comics

This repo now holds a Hugo-powered static archive of the original Funny Guy Comics site. The historic PHP implementation (and its MySQL dump) are still present for reference, but new work should happen through Hugo.

## Getting started

- `hugo server` – live preview at <http://localhost:1313> using the Hugo source in this repo.
- `hugo` – builds the static site into `public-hugo/` (kept out of git ignore so the rendered snapshot can be published if desired).
- Static assets live under `static/` (comics, images, fonts, downloads). All comic markdown lives in `content/comics/` with metadata generated from `fgcomics.sql`.

## Data provenance

`fgcomics.sql` remains the source of truth for comic metadata. The seed scripts imported it into two Hugo data files:

- `data/comics.json` – each page of every comic with dimensions and transcripts.
- `data/collections.json` – the original "book" groupings displayed on the archive page.

If the SQL dump changes, regenerate those files and re-run `hugo` to rebuild the output.

## Legacy PHP notes

The previous PHP site is untouched inside `public/`. The existing TODO list for that stack is preserved below for posterity:

1. Enable PHP extensions in docker-compose.yml, they aren't working right now.
2. Change MySQL connection to SQLite, it is a static db and doesn't need to be on a server.
3. Implement CI/CD on GitHub, or install GitLab locally in my home environment.
