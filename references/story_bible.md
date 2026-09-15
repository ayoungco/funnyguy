# Story bible

Consolidated lore, pulled from material that was sitting unread in the
legacy dump (`/mnt/creative/funnyguy/Documents/`) — never in
git, never in `characters.md`. See `references/remix_material_audit.md`
for where each source file lives and what else is in the dump.
`characters.md` stays as the original short version; this is the fuller
picture for anything (captions, remix scripts, generated dialogue) that
needs to sound like it actually knows the series.

## Origin (from `FGComics History.odt` / `FGOAC History.docx`)

Started October 2000, 4th grade, Edgar C. Moore Elementary — the creator
(Adam, aka "Elemental," now going by Dr. Seinyor) drew a six-panel comic
called "The Funny Guy" about a stick figure with wild hair and a
charbroiled Magikarp, purely because Pokemon was the hype at the time. It
kept going because classmates kept asking for the next one.

The whole thing runs on real schoolyard history treated as continuity:

- **Koven** is a caricature of an actual classmate the creator "made fun
  of... since he was kind of a douche" — a real kid who got mad enough
  to start his own rival comic (wordless, starring "Repair Man," whose
  gimmick was killing Funny Guy by dipping him in lava) and declared war,
  which the comics dramatize as **the Koven Wars**. The actual in-universe
  "contract" text for Repair Man's later handoff survives verbatim in
  `references/legacy_text/assimilation_document.txt` — mock-legalese
  ("It has briefly come to our attention over a smidgeon of
  hydrogen-oxygen mix...") declaring Repair Man "now property of Funny
  Guy Comix Corporation."
- **Repair Man** started as Koven's character, got "acquired" by the
  Funny Guy side once Koven lost interest (an in-universe "contract" was
  drafted and signed by the whole class), and is now a Funny Guy
  character with his own arc (see below).
- The creator's in-universe company was renamed twice as the operation
  grew: **FGCI** (Funny Guy Comics Industry, 4th grade) -> **FGOAC**
  (Funny Guy Organized Association of Comics, 6th grade, after absorbing
  smaller rival "companies" like Mr. It Comics) -> **FGComics** (junior
  high, once a real website existed and "FGOAC" stopped meaning anything
  to anyone).
- Koven's own rival business, after giving up Repair Man, became "Mega
  Man Comics."
- Two full-length bonus books were made and physically sold/distributed
  to classmates: **An F.G. Christmas Carol** and **Attack of the
  Drones**.
- A friend (Steve) made an independent, non-canon comic called **Kare
  Bear Killer**, later folded into the same archive/site.
- The RPG (`../funnyguyrpg`) was the creator's solo junior-high project;
  v1.0.4 shipped in 2005.

## Setting: Funnyland

From `Miscellaneous Information 2012.odt`, in full, because it's short
and very much sets the tone:

> Funnyland is an island that surfaced and developed geologically in the
> center of the Atlantic Ocean. It is governed by a minimalistic
> decentralized representative democracy. It has a free market economy.

That deadpan-bureaucratic register for an island that "surfaced
geologically" is a good model for narration/caption voice in general —
see `references/remix_style.md`.

## Cast (from `FGComics Character Bios.docx`)

- **Funny Guy** ("Elemental Funny Guy") — the original. Started as a man
  of simple pleasures (crushing boomboxes underfoot, throwing
  undesirables at walls) before the Koven Wars forced him to "throw away
  his innocence and fight for his survival... or something along those
  lines." Went through a whole evolution chain of prefixes: Funny Guy ->
  Super -> Hyper -> Master -> Psy -> Superior -> Cataclysmic -> Inferno
  -> Eternal -> **Elemental** Funny Guy. Only the original gets to be
  called plain "Funny Guy" — everyone else in the family uses their
  prefix.
- **Junior (Funny Guy Jr.)** — youngest FG, introduced in the Koven Wars
  with "massive jaws and a speech impediment." Now part of the core
  group at the F.G. House, attends Funny Town Elementary.
- **Target (Funny Guy)** — team's firearms/explosives specialist,
  implied primary financial backer via mercenary work, "nigh-invincible
  protagonist" energy played completely straight. Signature bit: absurd
  overkill delivered as a shrug (killed a stone with two birds from 200m
  using a slingshot made of a used undergarment and a turkey wishbone,
  "simply on a dare" — then found out he'd misheard the dare entirely).
- **Metal Funny Guy** — evolved from "Razor Funny Guy," team's heavy
  metal musician and self-appointed internet expert on a subjective art
  form. Comedically overconfident, easily undercut ("But that's just
  detail.").
- **Repair Man** — self-employed, self-licensed, self-humiliating.
  Started as Koven's unstoppable weapon ("ace in the hole"), got shot
  into space in a box with Koven, vanished, then resurfaced in Funnyland
  with steadily deteriorating mental faculties after (specifically)
  witnessing someone eating dandelions. Now a minor-annoyance ally the
  FGs "begrudgingly" help. Loosely affiliated with the Evilies.
- **The Evil Scientist** — Funny Guy's first nemesis, motive left
  ambiguous ("inferiority complex or jealousy"), ran experiments on FG
  as test subjects with his pet monkey ("The Monkey") as an infiltration
  agent. Defected to become an ally during the Koven Wars, stuck around
  long enough to help stomp Serious Man, eventually rebranded himself
  The "Evil" Scientist (scare quotes and all) once his allegiance got
  murky.
- **Serious Man** — a later villain (introduced 5th grade per the
  history doc); despises everything silly/humorous, i.e. everything the
  FGs stand for. Bio entry in the source doc is literally the placeholder
  word "description" — never got written up, worth inventing/expanding
  for new material rather than treating as fixed canon.
- **Sir Disaprine** — named only; also an unfilled "description"
  placeholder in the source. The `Repair Man's Own(tm)` "0.5 bit
  encryption" gag credited to "Disaprine Productions ©1970" at the top of
  the bios doc suggests Disaprine is tied to Repair Man's whole
  in-universe brand of fake, comically insecure technology.
- **Bad Guy** — deliberately, literally just "Bad Guy": "Such an
  unspeakable evil has never been heard or seen in the history of the
  world." A gag about a villain too underdeveloped to have a real name,
  played straight in the bio itself.
- **The Evilies** — the loose villain faction/team Repair Man and others
  are "at least a minion of." Never given its own bio entry; treat as an
  umbrella label for antagonists rather than a fleshed-out group.

## Official book/arc structure (from `Documents/fgcomics_Collections.sql`)

This is a real, numbered season structure with actual blurb copy the
site used — pulled straight from the `Collections` table, not
reconstructed. `comic_number` ranges match the `comic_number` field
already in every `funnyguycomics` frontmatter file, so this can drive
release-plan framing (`docs/social_release_plan.md`) directly: caption
"Book 3: The Quest for Serious Man begins!" when #91 comes up, etc.

| # | Title | Range | Blurb |
|---|---|---|---|
| 1 | The Koven Wars | 1-50 | "The comics that started it all! ...comics so old that they're starting to show up on the collective radar of the local archaeologists." |
| 2 | Some Nice Comix | 51-90 | Made after a supervisor asked "why don't you make some nice comics" — a deliberate, temporary tone shift away from Koven-Wars-style callouts. |
| 3 | The Quest for Serious Man | 91-120 | Serious Man introduced as the new Evilie once Repair Man/Koven mostly exit. |
| 4 | Serious Man Returns | 121-160 | Serious Man back with a new team of comrades. |
| 5 | Live From Funnytown | 161-200 | Parent book for two standalone specials (below); features the "F.G. Telethon" raising money for Oxy-Moron via "an unknown cause." |
| 6 | Action Comics | 201-240 | Extended action-parody arcs (shrink rays, a pet roach, etc). |
| 7 | The Power of Graphite | 241-280 | Most detailed art of the run to that point; "the adventures get weirder and weirder." |
| 8 | Epic Comics | 281-320 | FGs teleported to Ever Grande Metropolis, "a city spanning three interconnected planets"; "features the NEO Photorealism process." |
| — | An F.G. Christmas Carol | #175 (child of Book 5) | Full free bonus book, distributed physically at Christmas. |
| — | Attack of the Drones | #186 (child of Book 5) | Full bonus book, sold same year as the Christmas Carol one. |
| — | Kare Bear Killer | uncollected | Steve's independent, non-canon comic, folded into the same archive. |

## Unproduced material worth mining for new content

Real, never-illustrated drafts sitting in `Documents/322.txt`,
`Documents/325.txt` (also duplicated under `scripts/`), and
`Documents/ideas.txt` / `Documents/FGComics Notebook.docx` — these are
raw material for *new* generative content, not just historical color:

- **322.txt** — a scripted "gaming camps" satire: Vikings steal an Xbox;
  a Guitar Hero-fueled evolution gag where Razor Funny Guy becomes Metal
  Funny Guy by playing too much Guitar Hero, parodying hipsters. Explicit
  stage direction in the file: do it in the *old* comic style so a dated
  topic doesn't read as try-hard-current.
- **325.txt** ("Hidden Driveway") — a full mini-script: Junior, Target,
  and Element (Elemental Funny Guy) at breakfast; Target wants a "Hidden
  Driveway" road sign; a soccer mom ignores it and gets sideswiped off a
  cliff by Target in a Hummer that "appears out of nowhere."
- **FGComics Notebook.docx** — a long flat list of one-line gag
  fragments and non-sequiturs (never assembled into comics) that's
  basically a primary-source demonstration of the tone
  `references/remix_style.md` asks for: meme-logic non-sequiturs, deadpan
  political tangents crashing into toilet humor, Modest-Proposal-style
  fake sincerity. Good to sample from directly for new captions/scripts
  rather than inventing new one-liners from scratch.
- **`FGCOMICS.docx` / `Ideas_Notes.docx`** — two more scratch idea lists in
  the same folder, missed by the first pass through `Documents/`, now at
  `references/legacy_text/fgcomics_idea_scratch.txt` and
  `references/legacy_text/design_notes_and_ideas.txt`. More gag fragments
  and unused character ideas (Roger Wilco, Grampa Funny Guy, a Cop
  "terrible at being undercover"), but `design_notes_and_ideas.txt` is the
  more valuable find: it's the creator's own articulated craft philosophy
  for the comic, not just gag material — "The GRID can make things boring
  sometimes, don't be afraid to mix up the panes," "the frame that begins
  a new line seems to work well for punchlines," "Humor first, story
  SECOND," "BE RELATABLE." Worth treating as a design constraint for any
  new generated panel layouts, the same way `references/remix_style.md`
  treats the Notebook's tone as already-on-file rather than something to
  invent.

## Public site history (from `Documents/FGComics News (2005).txt`)

13 public news-post announcements from the fgcomics.com homepage,
2005–2007, cleaned up and dated at
`references/legacy_text/fgcomics_news_2005-2007.md`. Mostly FGRPG release/
patch history and site-maintenance chatter, but two are worth knowing
about directly: the 2005-12-24 "Conversion Update" post is the creator
describing, in real time, salvaging the original FGOAC character bios
after a hosting hack — corroborates `character_bios_2012_draft.txt`
existing as a separate draft from the main bios doc — and the 2007-01-02
post is the creator's own account of the "p3stil" defacement already
flagged in `references/remix_material_audit.md` via the `Turkish Hackers
Response` PSD.

## What's deliberately left out here

Legal/business documents (contracts, copyright ownership, employee
sheets) and personal email correspondence also live in `Documents/` —
seen during this audit, not summarized or quoted here. They're not
creative/lore material and don't belong in a reference file meant to
seed generated content.
