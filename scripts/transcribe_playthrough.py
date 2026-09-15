#!/usr/bin/env python3
"""
Transcribe recordings from /mnt/creative/funnyguy/Playthrough/ --
9GB of video previously invisible to every tool in this repo (discover_assets.py
only catalogs image/design extensions). Only the two files with "FGRPG" /
walkthrough naming are run here -- the "Phil's House"/"Room Noise Sample"
recordings in the same folder read as an unrelated personal recording
session, not Funny Guy material, so they're left alone pending a decision
on whether they're even in scope (see docs/progress.md).

Uses faster-whisper (large-v3, GPU) via ../../superfamily's existing
transcription venv, per references/audio_narration.md -- reusing that
project's working pipeline instead of standing up a separate one here.

Run with that venv's interpreter directly (has faster-whisper + CUDA torch):
    /home/adamyoung/src/superfamily/.venv-transcription/bin/python3 transcribe_playthrough.py <video_path>

Input:  any video/audio file (ffmpeg extracts the audio track)
Output: ../output/playthrough_transcripts/<name>.txt   (plain text)
        ../output/playthrough_transcripts/<name>.srt   (timestamped)
"""
import argparse
import subprocess
import sys
import tempfile
import time
from pathlib import Path

from faster_whisper import WhisperModel

SCRIPT_DIR = Path(__file__).resolve().parent
OUT_DIR = SCRIPT_DIR.parent / "output" / "playthrough_transcripts"

MODEL_NAME = "large-v3"


def extract_audio(video_path: Path, wav_path: Path):
    cmd = ["ffmpeg", "-y", "-i", str(video_path), "-vn", "-ar", "16000", "-ac", "1", "-c:a", "pcm_s16le", str(wav_path)]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print(result.stderr[-4000:], file=sys.stderr)
        sys.exit(1)


def format_srt_time(t: float) -> str:
    h, rem = divmod(t, 3600)
    m, s = divmod(rem, 60)
    ms = int((s - int(s)) * 1000)
    return f"{int(h):02d}:{int(m):02d}:{int(s):02d},{ms:03d}"


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("video_path", type=Path)
    parser.add_argument("--name", default=None, help="output basename (default: derived from input filename)")
    args = parser.parse_args()

    name = args.name or args.video_path.stem
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    print(f"loading faster-whisper {MODEL_NAME} on GPU...", file=sys.stderr)
    model = WhisperModel(MODEL_NAME, device="cuda", compute_type="float16")

    with tempfile.TemporaryDirectory() as tmp:
        wav_path = Path(tmp) / "audio.wav"
        print(f"extracting audio from {args.video_path}...", file=sys.stderr)
        extract_audio(args.video_path, wav_path)

        print("transcribing (this can take a while for long recordings)...", file=sys.stderr)
        t0 = time.time()
        segments, info = model.transcribe(str(wav_path), language="en", vad_filter=True)

        txt_lines, srt_lines = [], []
        for i, seg in enumerate(segments, start=1):
            txt_lines.append(seg.text.strip())
            srt_lines.append(f"{i}\n{format_srt_time(seg.start)} --> {format_srt_time(seg.end)}\n{seg.text.strip()}\n")
            if i % 50 == 0:
                print(f"  ...{seg.end:.0f}s of audio processed ({time.time() - t0:.0f}s elapsed)", file=sys.stderr)

    (OUT_DIR / f"{name}.txt").write_text("\n".join(txt_lines))
    (OUT_DIR / f"{name}.srt").write_text("\n".join(srt_lines))
    print(f"wrote {len(txt_lines)} segments to {OUT_DIR / (name + '.txt')} / .srt in {time.time() - t0:.0f}s")


if __name__ == "__main__":
    main()
