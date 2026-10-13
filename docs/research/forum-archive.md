# Forum archive research

Aggregate findings only. No private messages, emails, IPs, password hashes or
member names are recorded here, and none should be committed to this repo.

## Source

`/mnt/hdd/assets/funnyguy/FGComics - Website.zip` (the old site backup, 2,085
files) holds the forum databases under `FGComics - Website/_OFFLINE/backups/`.
The `FGComics II/phpBB3/` folder is an empty stock phpBB3 install (installer seed
rows only) and has no content.

## Two consecutive forums

| Software | Dump | Covers | Posts | Topics | Notes |
|---|---|---|---|---|---|
| phpBB 2.0.11 | `phpBB Database/{June,August,October,December}, 2005/phpbb_db_backup.sql` | 2005-02-24 to 2005-12-13 | 4,512 (Dec) | 221 | Backups are cumulative snapshots, so December supersedes the rest (698 / 2,421 / 3,249 / 4,512 posts). 15 users, 26 polls, 10 forums. |
| Invision Power Board 2.x (`ibf_`) | `old dbs/fgcomics_forums.sql` (phpMyAdmin dump, 2007-01-02) | 2005-12-17 to 2006-12-29 | 4,123 | 189 | 19 members, 554 posts with images, no attachments. 12 MB, but most of it is spider/task logs (about 65K rows) that can be dropped. |

Together: about 8,600 posts over 22 months, with a four-day gap between the
phpBB dump (Dec 13) and the first IPB post (Dec 17). Most traffic is in
Chatterbox (1,288 phpBB posts, 3,362 IPB posts), then Media, Halo, Website and
Games. phpBB2 busiest months: Jul to Oct 2005 (800 to 925 posts a month).
Post volume is concentrated in a few users (top two have 1,200 and 861 posts).

Other dumps in `old dbs/`: zipped `fgcomics_news` and `fgcomics_eComics` (2005),
not yet opened. `IPB Layout/December, 2005/` holds the IPB "elemental" skin and
image set as XML.

## Encoding and format notes

- phpBB2 dumps are latin-1 with BBCode and a `bbcode_uid`; posts are in
  `phpbb_posts` (metadata) and `phpbb_posts_text` (body, subject).
- IPB posts are HTML with `<#EMO_DIR#>` emoticon placeholders; the emoticon
  image sets and avatars are not in the SQL and need to be found or rebuilt.
- Text volume is small: about 0.28 M chars (phpBB) plus 0.7 M chars (IPB).

## Privacy

Both dumps include private messages (217 phpBB, 76 IPB), IP addresses, emails
and password hashes, and the authors are mostly teenagers who are now adults.
Do not publish those. Before anything goes public, strip them, and decide
between: nothing, titles plus the owner's posts, or a full read-only archive
with an opt-out.

## Resurrection options

1. **Static archive (recommended first).** Normalize both dumps to one
   SQLite/JSON, then render per-topic pages (Hugo) with search. No server,
   nothing to patch, easy to redact. Rebuild BBCode and IPB HTML to clean HTML.
2. **Modern phpBB, read-only.** phpBB 3.3's installer ships
   `install/convertors/convert_phpbb20.php`, a direct phpBB 2.0 upgrade path,
   so the 2005 half converts natively. The IPB half would need a community
   converter or a custom import. Needs PHP and MySQL, and ongoing patching.
3. **Both:** static archive public, a phpBB instance kept privately.

## Reproduce

`forum_stats.py` (this directory) prints the counts above from an
unpacked backup: `python3 -I forum_stats.py "<unzipped>/_OFFLINE/backups"`.
It reads files only and prints aggregates.

## Normalized database

`forum_normalize.py` merges both boards into one SQLite file with `forums`,
`topics` and `posts` (source, ids, author name, timestamp, subject, raw body).
It deliberately omits private messages, IPs, emails and password hashes. The
output lives outside the repo at `~/fgcomics-originals/forum/forum.sqlite`.

Result: phpBB 4,512 posts / 221 topics / 20 authors; IPB 4,123 posts / 189
topics / 20 authors; no orphan posts and no empty bodies. Gotcha: some phpBB
posts contain raw newlines, so the dump must be parsed by statement, not by line.
Bodies are still raw (BBCode and IPB HTML with `<#EMO_DIR#>` smilies); the
rendering step is next.
