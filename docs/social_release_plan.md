# Social release plan

How the `output/shorts/` clips (see `scripts/make_short.py`) get from
"rendered" to "posted." Generated/maintained by
`scripts/build_schedule.py` -> `output/social_schedule.csv`; this doc is
the reasoning behind that script's defaults, not a replacement for it.

## Platforms

TikTok, Instagram Reels, YouTube Shorts. Same rendered file to all three
per post -- 1080x1920, already captioned, no per-platform edit needed.

## Order: chronological, not shuffled

Release by original `comic_number`, oldest first. This is a 20+ year-old
webcomic archive with real continuity (recurring characters, an actual
timeline), not a bag of disconnected gags -- "watch the series from
issue #1" is a stronger hook than a random grab-bag, especially for the
found-footage/nostalgia framing (a Y2K-era kid's notebook comic being
rediscovered) that the source material supports for free. Costs nothing
to do since `comic_number` is already in `funnyguycomics` frontmatter.

Both segmentation-confidence tiers (`ok` and `ok_low_confidence`, see
`docs/progress.md`) are included in the queue -- spot checks on a few
low-confidence comics during development looked fine. The tier is kept as
a column in the schedule CSV so a low-confidence post can get a 10-second
manual glance before its slot comes up, without holding up the queue.
The 88 comics segmentation failed entirely on have no clip and aren't in
the queue at all.

## Cadence: 4/week (Mon/Wed/Fri/Sun), 7pm local, one post = one cross-post

4x/week across ~250 clips is about a 15-month runway. That's a deliberate
choice over daily posting: this is unattended archive content with no new
production cost per post, so there's no reason to burn through it fast,
and a lower, sustainable cadence is easier for a person to actually keep
up with the manual upload step (see below) three platforms at a time
without it becoming a chore that gets dropped after a month. Daily
posting is the more common growth advice for a *new* account trying to
train the algorithm fast -- worth switching to once/if early performance
data says the slower cadence is leaving reach on the table, not before.

7pm local is a generic reasonable-engagement-window default, not
audience-specific data (there isn't any yet). Revisit using each
platform's own analytics (TikTok/IG both show follower active-hours)
after a few weeks of real posts.

Re-run `build_schedule.py --days ... --time ... --start ...` to change
any of this -- it regenerates the CSV from whatever's in `output/shorts/`
at the time, so it's cheap to redo as the plan changes.

## Captions & hashtags

Each row's `suggested_caption` is generated, not hand-written: issue
number + title, one hook line (the *first* captioned line in the clip,
not the last, so the on-platform caption doesn't spoil the punchline the
clip itself builds to), then a fixed evergreen hashtag set
(`#funnyguy #webcomic #stickfigure #handdrawn #y2k #2000s #comics
#ocarchive`). Treat it as a draft to skim before pasting, not a
copy-paste-blind final -- it doesn't know which issues are especially
funny or worth a different hook.

## Why this doesn't auto-post

Actually publishing programmatically (rather than generating the queue)
was considered and dropped for now:

- **Instagram/Meta**: the Content Publishing API needs a Business/Creator
  account linked to a Facebook Page and an app that's been through Meta's
  App Review for the publish permission -- real overhead for a personal
  archive project. Meta Business Suite's own web scheduler does the same
  job (schedule a Reel for a future time) with no API/app-review step at
  all -- use that instead of building against the API.
- **TikTok**: the Content Posting API's direct-publish scope is similarly
  gated behind developer app approval. No first-party web scheduler
  exists for personal accounts either, so this is the one platform where
  a third-party scheduler (Later/Buffer/Metricool all have free tiers
  covering TikTok) or manual upload-on-the-day is the practical path.
- **YouTube**: easiest of the three -- YouTube Studio's native uploader
  lets you pick a future publish date/time directly, no API needed.

None of that needs credentials or OAuth handled by anything in this repo,
which also keeps this project out of the business of holding social-media
account secrets. If it's ever worth automating for real, that's a
separate, deliberate piece of work (register a developer app per
platform, go through review, store tokens somewhere real) -- not
something to back into via this script.

## Workflow

1. `scripts/build_schedule.py` (re-run after every `make_short.py` batch)
   regenerates `output/social_schedule.csv`, ordered and dated.
2. On or before each `post_datetime`, upload `video_path` to all three
   platforms (directly, or queued in Meta Business Suite / YouTube Studio
   for that exact time; TikTok via a scheduler or same-day manual post),
   using `suggested_caption` as a starting point.
3. Mark the `posted` column once done -- it's not read by the script, just
   a manual tracking column so re-running `build_schedule.py` (e.g. after
   rendering more shorts) doesn't make it unclear what's already gone out.
