# phpBB 3.3 import (2026-10-23)
Pipeline: raw dumps -> `scripts/forum_reclaim_build.py` -> `forum_private.sqlite` -> `scripts/forum_export_for_phpbb3.py` -> `import.json` (private, delete after) -> `repo/forums/dev/dev.sh import import.json` (runs `repo/forums/import/import_archive.php` + `resync.php`).
Fresh rebuild from nothing: `dev.sh reset && dev.sh up && dev.sh install && dev.sh import <json>` (verified end to end).

## Result (stock prosilver, localhost:8081)
- 2 categories (2005 board, 2006 board), 20 forums, 405 topics (5 empty topics dropped by phpBB's sync), **8,634 posts** with original timestamps. Per-forum counts match the research (Chatterbox 1,288 + 3,484).
- 35 accounts (3 usernames span both eras) + 5 archive-only stubs (no email, inactive; admin can reactivate). One post (the PHP include attack) is dropped.
- **Reclaim = phpBB's "I forgot my password"**: accounts have an unusable password (`*`) and their original email; no hashes imported. Emails need a mail catcher locally and real SMTP in production.
- No IPs stored (posts and users). PMs, avatars, ranks, polls (26 in 2005), attachments and the full-text search index are **not** imported yet.
- Conversion: phpBB2 BBCode uid suffixes stripped, entities decoded; IPB HTML -> BBCode (quotes, color, size, lists, links, images); IPB emoticons -> phpBB3 smilie codes. Spot-checked by eye on the 157-post "Official music discussion" thread.

## Which theme did the forums use? (evidence)
- **2005 phpBB 2.0.11**: `phpbb_config.default_style = 8` = **"Elemental"**, with `override_user_style = 1`. Themes table: subSilver, a red subSilver variant named "fgcomics", an "fgcomics" theme, and Elemental. So your memory of "blue silver" is right in spirit: custom template on a subSilver base, blue by default.
- Source files survive in `repo/fgcomics2006/forums/templates/Elemental/` (templates, 56 images, `Elemental.css`, plus `fgcomics_blue.css`, `fgcomics_green.css`, `fgcomics_red.css`). The CSS header explains the method: one Photoshop design, other colors made by **changing hue only**; images live under `/images/<color>/` with shared `/images/layout/`.
- `repo/fgcomics2006/skinchanger.php` (started 2005-07-27) set a `skinpref` cookie, default `blue`: your JS/PHP theme switcher.
- **2006 Invision board**: the dump folder holds `ipb_skin-elemental` and `ipb_images-elemental` XML, so the same Elemental look was ported to IPB.
- **2008**: Wayback has captures of `fgcomics.com/forum/` pages (index, faq, memberlist, search, `ucp.php`) from 2008-05-03, i.e. a **third, phpBB3-era board** we have no dump for.
- Next: rebuild Elemental as a selectable phpBB3 style (blue/green/red) from those assets, keeping prosilver as the default.
