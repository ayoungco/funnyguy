# Audio / narration — stub

Not built. Noting the idea before it's lost.

If the slideshow videos (see `../scripts/make_slideshow.py`) ever get narration
or character voice lines, `../../superfamily` already has a working
transcription/voice pipeline worth reusing instead of starting from
scratch:

- `../../superfamily/docs/audio-extraction-and-transcription.md` — audio
  extraction + Whisper-based transcription workflow.
- `../../superfamily/docs/voice-generative.md` — voice reconstruction /
  generative voice notes (referenced from the doc above, not yet read in
  detail here).
- A `.venv-transcription` venv already exists in that repo.

Speaker diarization + per-character voice profiles could map onto Funny
Guy's cast (Funny Guy, Koven, Repair Man, Serious Man, etc. — see
`characters.md`) the same way superfamily maps them onto its own cast.
Worth a proper look when narration actually becomes a goal, not before.
