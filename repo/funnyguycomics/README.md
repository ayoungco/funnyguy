# Funny Guy Comics

This repo now holds a Hugo-powered static archive of the original Funny Guy Comics site. The historic PHP implementation (and its MySQL dump) are still present for reference, but new work should happen through Hugo.

## Getting started

- `hugo server` – live preview at <http://localhost:1313> using the Hugo source in this repo.
- `hugo --minify -d <dir>` – builds the comics site alone into `<dir>`. For the deployable site (with the game) use `scripts/build-site.sh` below.
- Static assets live under `static/` (comics, images, fonts, downloads). All comic markdown lives in `content/comics/` with metadata generated from `fgcomics.sql`.

## Build and deploy

`scripts/build-site.sh [OUT]` (from the `funnyguy` repo root) builds the whole of
www.fgcomics.com: the Hugo site plus the browser-playable FGRPG at `/game/play/`
(EasyRPG web player from `repo/funnyguyrpg/web/`, game files from
`repo/funnyguyrpg/game/`, `index.json` regenerated each build). Default `OUT` is
`repo/funnyguycomics/public/` (gitignored). No PHP, no database.

Forge (`divine-winter`), repo `ayoungco/funnyguy`, push-to-deploy, web directory `/public`:

```sh
$CREATE_RELEASE()
cd $FORGE_RELEASE_DIRECTORY
scripts/build-site.sh public
$ACTIVATE_RELEASE()
```

Needs `hugo` (extended not required) on the server. Nginx additions for the site:

```nginx
# EasyRPG needs cross-origin isolation; keep it off the rest of the site.
location /game/play/ {
    add_header Cross-Origin-Opener-Policy same-origin always;
    add_header Cross-Origin-Embedder-Policy require-corp always;
    try_files $uri $uri/ =404;
}
# Old PNG comic URLs now serve WebP.
location ~ ^/comics/(.+)\.png$ { try_files /comics/$1.webp =404; }
```

`/game/play` 301s to `/game/play/` (nginx does this for directories); the player
resolves `games/default/` relative to the page, so the trailing slash matters.
Both fgcomics.com and funnyguycomics.com point at this one site; canonical links
name `www.fgcomics.com`.

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
