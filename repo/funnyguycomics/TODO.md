# TODO

Factory/workflow work (rescans, segmentation, video, generation) lives in ../funnyguy; this repo is just the publishing website.

## Plan

- www.fgcomics.com is canonical; funnyguycomics.com 301s to it (path-preserving, set at host/DNS), with `rel=canonical` and sitemap on fgcomics.com.
- Archive of the site's visual history: one preserved static copy per era (`/eras/<year>/`) plus a `/history/` timeline. Link from ayoung.co `/archive/`.
- Publish all unpublished FG designs (location TBD).

## Done

- Removed `legacy/` assets duplicated in `static/` (406 files, byte-identical); `legacy/` is now just the PHP/CSS reference (108K).
- Converted `static/comics` PNG -> WebP (near-lossless 60, same dimensions): 241 MB -> 40 MB. Content front matter updated.
- Originals backed up outside the repo at `~/fgcomics-originals/` (comics PNGs + `fgrpg-1.0.4-win.zip`).
- Dropped the 24 MB RPG zip from the tree; `content/game.md` shows "coming soon" until it is hosted as a release.
- Wayback inventory (CDX, nothing downloaded): fgcomics.com has 381 unique captures 2005-2025 (313 are 2022 noise), ~28 distinct homepage versions; only 13 images captured, so early eras will be partly broken. funnyguycomics.com has 7 captures (2018, 2024).

- Legacy routes revived: `/N/`, `/N/page/` aliases, `/rss.xml`, 404 page, canonical links, robots.txt; site builds again (moved `fgcomics.sql` out of `data/`).

## Next

- Deploy via infra (`static_site`): PNG->WebP rewrite for old `/comics/*.png` URLs, apex/funnyguycomics.com -> www 301.

- Pick one snapshot per visually distinct homepage era, screenshot, then fetch raw (`id_`) pages for the chosen ones only (mind the 429 rate limit; use a User-Agent and backoff).
- Locate unpublished designs: `/mnt/assets` and `~/src/portfolio` do not exist on this machine; `~/src/fgoac` too. Find where they live.
- Host the RPG zip (GitHub release) and relink `content/game.md`.
- History scrub: `.git` is still ~308M because old PNGs and the zip remain in history. Back up with `git clone --mirror`, then `git filter-repo` to drop `static/comics/*.png`, `legacy/comics/*.png` and the zip, then force-push. Not done yet.
- Redirect funnyguycomics.com -> fgcomics.com at the host.
