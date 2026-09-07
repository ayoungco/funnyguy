# Remix tone: Eric Andre x Homestar Runner x Stephen Wright

Target voice for *generated/remixed* content (alt-dialogue, mashup
captions, new scripts built from unproduced material) — not a
replacement for the archive's own original tone when just re-presenting
existing comics as-is (`make_short.py` etc. should keep using the
comic's real transcribed dialogue for that).

## The three references, and what to take from each

- **Eric Andre** — commit fully to a non-sequitur with zero acknowledgment
  that anything strange happened. The tonal whiplash *is* the joke; never
  wink at the audience or explain the bit afterward.
- **Homestar Runner** — lo-fi, home-made, aggressively unpolished-on-
  purpose, extremely online-before-"online-brained" was a genre. Internet
  culture referenced sincerely, not ironically-above-it. This is close to
  free: the source material already *is* a Y2K notebook-paper webcomic
  made by an actual kid — lean into that being the format, don't try to
  make it look slicker.
- **Stephen Wright** — the delivery, not the subject: flat, matter-of-fact
  narration for something absurd, no punchline emphasis, let the gap
  between tone and content do the work. Good model for burned-in caption
  copy and any narration track.

## This isn't an artificial style graft

The legacy dump's own unpublished material (`references/story_bible.md`,
"Unproduced material" section) is already most of the way there without
any steering: `FGComics Notebook.docx` is a flat list of gag fragments
that reads like a Stephen Wright bit crossed with a meme-logic Twitter
account — a redneck raging at a jar because it says "apply liberally," a
"Founding Fatherhood" satire disclaiming historical accuracy up front, a
hangover-cure recipe dropped with zero framing next to Team Fortress 2
class rankings. `325.txt` ("Hidden Driveway") already plays a soccer-mom
death by Hummer completely straight, mid-breakfast-scene, no reaction
shot. Pull from these files directly for new captions/scripts before
inventing new one-liners — the target tone is already on file, not
something to synthesize from scratch.

## Applying it

- **New captions over real panels**: keep the actual transcribed dialogue
  as the primary text (that's the artifact, not something to rewrite),
  but an *added* line (title-card text, a video description, a "previously
  on Funnyland" style recap) can use this voice.
- **Remixed/mashup scenes** (compositing isolated character layers from
  `Raw/*.psd`, see `references/remix_material_audit.md`, into new
  arrangements): favor flat, declarative staging over frantic
  action-movie energy — the Wright influence — with an Andre-style hard
  cut into the absurd premise, no build-up.
- **Illustrating the unproduced scripts** (`322.txt`, `325.txt`): these
  already read as normal sitcom scenes until one specific detail breaks
  reality (a cliff appearing out of nowhere, Guitar Hero causing an
  actual evolution). Keep everything else played straight so that one
  break lands.
- **Avoid**: cutaway-gag-with-a-wink structure (Family Guy-style "remember
  when" non-sequiturs that announce themselves as jokes), meta-humor about
  the video being a video, and modern internet-slang references — the
  Homestar Runner influence is about being sincerely of its own era, not
  winking at being retro.
