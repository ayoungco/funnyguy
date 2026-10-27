# Private reclaim database (2026-10-23)
Built by `scripts/forum_reclaim_build.py` from the raw phpBB2 (Dec 2005) and IPB (Jan 2007) dumps into
`/mnt/hdd/projects/funnyguy/forum/forum_private.sqlite` (mode 600, **contains emails and PMs: never commit, never publish**).
Rebuild: unzip the two dumps from `FGComics - Website.zip` (`_OFFLINE/backups/`) to a scratch dir, run the script, delete the scratch dir (raw dumps hold hashes, IPs and PMs).

## Policy (decided with the owner)
- **Dropped:** password hashes/salts, login/session/activation keys, all IPs, browser strings, birthdays, every log table (spider, task, admin, moderator, session, search, email).
- **Kept:** usernames, emails, join dates, post counts, profile fields, posts/topics/forums, and **PMs (private store only)**.
- **PMs:** kept in the private DB, imported nowhere yet; decide per member at reclaim time.
- The script runs a leak check (no secret-named columns, no 32-hex values in member fields) and deletes its output if it fails.

## Contents
| | phpBB 2005 | IPB 2005-06 |
|---|---|---|
| posts / topics | 4,512 / 221 | 4,123 / 189 |
| members (all with email) | 19 | 19 |
| PMs | 217 | 76 |
- 38 member rows = **31 unique people by email**. Only 3 usernames appear in both eras but 7 emails do, so people renamed between boards: **link accounts by email, not username**.
- 6 post authors have no member row (deleted or renamed accounts, incl. "Anonymous" and a few handles); they have no email, so reclaim is manual for them.
- 51 IPB converge/extra rows with no member row are **spam registrations**: all `bot-test.info` addresses, registered 2006-11-26/27. Not imported.
- Email domains skew 2000s ISP/webmail (yahoo, aol, hotmail, suscom, verizon, comcast, msn): expect some dead addresses; keep a manual-vouch path.
