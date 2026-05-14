#!/usr/bin/env python3
"""Extract storyboard/comic panels from scanned pages.

This starts with the OpenCV contour workflow from docs/ideas/video.md and keeps
PyTorch available for later learned segmentation models.
"""

from __future__ import annotations

import argparse
import json
from dataclasses import asdict, dataclass
from pathlib import Path
from typing import Iterable

import cv2
import numpy as np
from tqdm import tqdm


IMAGE_EXTENSIONS = {".jpg", ".jpeg", ".png", ".tif", ".tiff", ".webp", ".bmp"}


@dataclass(frozen=True)
class Panel:
    page: str
    index: int
    x: int
    y: int
    width: int
    height: int
    score: float
    output: str


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Detect rectangular comic/storyboard panels and export crops."
    )
    parser.add_argument("input", type=Path, help="Image file or directory of pages.")
    parser.add_argument(
        "--output",
        type=Path,
        default=Path("panel-output"),
        help="Directory for cropped panels.",
    )
    parser.add_argument(
        "--debug-dir",
        type=Path,
        help="Optional directory for images showing detected panel boxes.",
    )
    parser.add_argument(
        "--min-area-ratio",
        type=float,
        default=0.015,
        help="Drop boxes smaller than this fraction of the page area.",
    )
    parser.add_argument(
        "--max-area-ratio",
        type=float,
        default=0.92,
        help="Drop boxes larger than this fraction of the page area.",
    )
    parser.add_argument(
        "--padding",
        type=int,
        default=8,
        help="Pixels to include around each detected crop.",
    )
    parser.add_argument(
        "--row-tolerance",
        type=float,
        default=0.45,
        help="Reading-order row grouping tolerance as a fraction of median box height.",
    )
    parser.add_argument(
        "--manifest",
        type=Path,
        default=Path("panel-output/manifest.json"),
        help="JSON manifest of exported panels.",
    )
    parser.add_argument(
        "--device",
        choices=["auto", "cpu", "mps", "cuda"],
        default="auto",
        help="PyTorch device preference for future learned segmentation passes.",
    )
    return parser.parse_args()


def iter_images(path: Path) -> Iterable[Path]:
    if path.is_file() and path.suffix.lower() in IMAGE_EXTENSIONS:
        yield path
        return

    if path.is_dir():
        for item in sorted(path.rglob("*")):
            if item.is_file() and item.suffix.lower() in IMAGE_EXTENSIONS:
                yield item
        return

    raise FileNotFoundError(f"No readable image file or directory found: {path}")


def select_torch_device(preference: str) -> str:
    try:
        import torch
    except ImportError:
        return "torch-not-installed"

    if preference != "auto":
        return preference
    if torch.cuda.is_available():
        return "cuda"
    if hasattr(torch.backends, "mps") and torch.backends.mps.is_available():
        return "mps"
    return "cpu"


def detect_boxes(
    image: np.ndarray,
    min_area_ratio: float,
    max_area_ratio: float,
) -> list[tuple[int, int, int, int, float]]:
    height, width = image.shape[:2]
    page_area = width * height

    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    threshold = cv2.adaptiveThreshold(
        blurred,
        255,
        cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY_INV,
        41,
        9,
    )

    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (9, 9))
    closed = cv2.morphologyEx(threshold, cv2.MORPH_CLOSE, kernel, iterations=2)
    contours, _ = cv2.findContours(closed, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    boxes: list[tuple[int, int, int, int, float]] = []
    for contour in contours:
        x, y, box_width, box_height = cv2.boundingRect(contour)
        area = box_width * box_height
        area_ratio = area / page_area
        if area_ratio < min_area_ratio or area_ratio > max_area_ratio:
            continue
        if box_width < 40 or box_height < 40:
            continue

        contour_area = cv2.contourArea(contour)
        extent = contour_area / area if area else 0
        aspect = box_width / box_height
        if aspect < 0.2 or aspect > 8.0:
            continue

        score = min(1.0, max(0.0, extent))
        boxes.append((x, y, box_width, box_height, score))

    return suppress_nested_boxes(boxes)


def suppress_nested_boxes(
    boxes: list[tuple[int, int, int, int, float]],
) -> list[tuple[int, int, int, int, float]]:
    kept: list[tuple[int, int, int, int, float]] = []
    for box in sorted(boxes, key=lambda item: item[2] * item[3], reverse=True):
        x, y, width, height, _ = box
        area = width * height
        duplicate = False
        for kept_box in kept:
            kx, ky, kw, kh, _ = kept_box
            ix1, iy1 = max(x, kx), max(y, ky)
            ix2, iy2 = min(x + width, kx + kw), min(y + height, ky + kh)
            intersection = max(0, ix2 - ix1) * max(0, iy2 - iy1)
            if intersection / area > 0.85:
                duplicate = True
                break
        if not duplicate:
            kept.append(box)
    return kept


def sort_reading_order(
    boxes: list[tuple[int, int, int, int, float]],
    row_tolerance: float,
) -> list[tuple[int, int, int, int, float]]:
    if not boxes:
        return []

    median_height = float(np.median([height for _, _, _, height, _ in boxes]))
    tolerance = max(16.0, median_height * row_tolerance)
    rows: list[list[tuple[int, int, int, int, float]]] = []

    for box in sorted(boxes, key=lambda item: (item[1], item[0])):
        _, y, _, height, _ = box
        center_y = y + height / 2
        for row in rows:
            row_center = np.mean([row_box[1] + row_box[3] / 2 for row_box in row])
            if abs(center_y - row_center) <= tolerance:
                row.append(box)
                break
        else:
            rows.append([box])

    sorted_boxes: list[tuple[int, int, int, int, float]] = []
    for row in rows:
        sorted_boxes.extend(sorted(row, key=lambda item: item[0]))
    return sorted_boxes


def crop_with_padding(
    image: np.ndarray,
    box: tuple[int, int, int, int, float],
    padding: int,
) -> np.ndarray:
    x, y, width, height, _ = box
    image_height, image_width = image.shape[:2]
    x1 = max(0, x - padding)
    y1 = max(0, y - padding)
    x2 = min(image_width, x + width + padding)
    y2 = min(image_height, y + height + padding)
    return image[y1:y2, x1:x2]


def write_debug_image(
    image: np.ndarray,
    boxes: list[tuple[int, int, int, int, float]],
    output: Path,
) -> None:
    debug = image.copy()
    for index, (x, y, width, height, _) in enumerate(boxes, start=1):
        cv2.rectangle(debug, (x, y), (x + width, y + height), (26, 26, 178), 4)
        cv2.putText(
            debug,
            str(index),
            (x + 8, y + 32),
            cv2.FONT_HERSHEY_SIMPLEX,
            1.0,
            (26, 26, 178),
            3,
            cv2.LINE_AA,
        )
    output.parent.mkdir(parents=True, exist_ok=True)
    cv2.imwrite(str(output), debug)


def export_page(
    page: Path,
    output_dir: Path,
    debug_dir: Path | None,
    args: argparse.Namespace,
) -> list[Panel]:
    image = cv2.imread(str(page))
    if image is None:
        raise ValueError(f"Could not read image: {page}")

    boxes = detect_boxes(image, args.min_area_ratio, args.max_area_ratio)
    ordered_boxes = sort_reading_order(boxes, args.row_tolerance)

    page_slug = page.stem.lower().replace(" ", "-")
    page_output_dir = output_dir / page_slug
    page_output_dir.mkdir(parents=True, exist_ok=True)

    panels: list[Panel] = []
    for index, box in enumerate(ordered_boxes, start=1):
        crop = crop_with_padding(image, box, args.padding)
        output = page_output_dir / f"{page_slug}_panel{index:03d}.png"
        cv2.imwrite(str(output), crop)
        x, y, width, height, score = box
        panels.append(
            Panel(
                page=str(page),
                index=index,
                x=x,
                y=y,
                width=width,
                height=height,
                score=round(score, 4),
                output=str(output),
            )
        )

    if debug_dir:
        write_debug_image(image, ordered_boxes, debug_dir / f"{page_slug}_debug.png")

    return panels


def main() -> int:
    args = parse_args()
    device = select_torch_device(args.device)
    pages = list(iter_images(args.input))
    if not pages:
        raise SystemExit(f"No image files found under {args.input}")

    args.output.mkdir(parents=True, exist_ok=True)
    all_panels: list[Panel] = []
    for page in tqdm(pages, desc=f"Segmenting panels [{device}]"):
        all_panels.extend(export_page(page, args.output, args.debug_dir, args))

    args.manifest.parent.mkdir(parents=True, exist_ok=True)
    args.manifest.write_text(
        json.dumps([asdict(panel) for panel in all_panels], indent=2) + "\n",
        encoding="utf-8",
    )
    print(f"Exported {len(all_panels)} panels from {len(pages)} page(s).")
    print(f"Manifest: {args.manifest}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
