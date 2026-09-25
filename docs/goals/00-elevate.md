# goal 00: Elevate Funny Guy Comics 

# Project Context & AI Agent Directives

## Project Overview
This repository manages an automated pipeline for digitizing, extracting, and animating a legacy pencil-drawn comic universe. The goal is to ingest raw scanned artwork, use local AI models to isolate character assets, standardize them into a repository, and programmatically generate animated slideshows or parallax video content.

## Architecture & Tech Stack
The infrastructure relies on local processing and self-hosted automation.
* **Orchestration:** n8n (containerized via Docker) handles file watching, workflow triggering, and routing.
* **Networking:** Tailscale is utilized for secure, remote ingestion of raw scans into the local drop folder.
* **AI Inference:** ComfyUI (running locally) handles image processing via API.
* **Core Models:** Segment Anything Model (SAM) for subject isolation/extraction, and Stable Video Diffusion (SVD) for parallax/motion generation.
* **Video Processing:** `ffmpeg` for stitching clips, adding audio, and applying transitions.
* **Scripting:** Python for utility scripts, API calls to ComfyUI, and file system management.

## Repository Structure & Git Rules
This repository is strictly for code, configuration, and final processed assets. It is NOT a backup for working design files.

* **TRACKED:** Python scripts, n8n workflow JSON exports, ComfyUI workflow JSON exports, shell scripts, and final extracted `.png` assets (stored in `/assets`).
* **UNTRACKED (Strict Rule):** You must never attempt to track, modify, or commit `.psd` (Photoshop), `.tif`, or raw, high-resolution `.jpg`/`.png` scan files. Ensure these remain in `.gitignore`.
* **Data Structure:** Narrative pacing and video generation sequences are managed via JSON files (e.g., `sequence_config.json`) mapping asset filenames to scene durations.

## Operational Pipeline
When writing scripts or workflows, adhere to this specific order of operations:
1.  **Ingestion & Leveling:** Raw scans are dropped into a designated watch folder. Scripts must apply a harsh contrast leveling (pure white background, black graphite lines) before passing to the AI.
2.  **Extraction:** The leveled image is sent to the ComfyUI API running SAM. The output is a cutout with a transparent alpha channel.
3.  **Standardization:** The resulting `.png` is renamed using a standardized convention (e.g., `[character]_[pose]_[id].png`), moved to the `/assets` directory, and committed.
4.  **Generation:** Scripts utilizing SVD generate short parallax video clips from the static assets.
5.  **Assembly:** `ffmpeg` scripts read the JSON sequence configuration to stitch the SVD outputs into a final cohesive slideshow video.

## Directives for AI Coding Agent
* **API First:** When interacting with ComfyUI, prioritize building modular Python functions that construct and send JSON payloads to the ComfyUI API endpoint.
* **Error Handling:** The n8n workflows and Python scripts must fail gracefully. If an image fails to extract via SAM, log the error and move the raw file to a `/failed_extractions` directory rather than halting the entire queue.
* **Dependency Management:** Keep Python dependencies minimal. Rely on standard libraries where possible, and explicitly document requirements for `requests` or image processing libraries like `Pillow` or `OpenCV`.
* **No Destructive File Operations:** Never write scripts that delete the raw source files after processing; always move them to an `/archived_scans` directory outside of the Git repository.