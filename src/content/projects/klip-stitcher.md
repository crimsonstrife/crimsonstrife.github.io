---
title: "Klip-Stitcher"
category: "tools"
summary: "A desktop app that stitches a folder of sequentially-named recording clips back into one continuous file — built after OBS quietly split a three-hour stream into hundreds of thirty-second fragments."
track: "independent"
year: 2026
order: 6
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/klip-stitcher-thumb.jpg"
  full: "../../assets/images/portfolio/klip-stitcher.jpg"
tags: ["Electron", "React", "TypeScript", "FFmpeg", "Vite", "Desktop App"]
links:
  repo: "https://github.com/crimsonstrife/klip-stitcher"
  download: "https://github.com/crimsonstrife/klip-stitcher/releases/tag/v0.1.0"
---

I streamed for about three hours on Twitch with the plan of handing the
recording to an editor to cut down for YouTube. When I went looking for the
file, there wasn't one — I'd misconfigured OBS, and instead of a single
recording I had several hundred clips of roughly thirty seconds each. Nobody
was going to accept that for editing without serious costs.

The saving grace was that the split points were clean: no dead air, no dropped
frames at the boundaries, so the pieces would join back together seamlessly if
someone had the patience to line them all up. I did not have that patience
several hundred times over, so I wrote something that did.

## Choosing the wrong tool on purpose

I'm a web developer, not really a desktop developer despite my game development work. 
Rather than spend the project learning a native toolkit, I looked for ways to build something
with the technologies I knew, and I was eventually pointed at Electron. It was my first time using it, and
picking a familiar surface for an unfamiliar problem is a large part of why the
thing got finished at all.  I had a small learning curve with React and Electron, 
but most of the work was ultimately about figuring out how the required libraries for the file handling worked.

## What it actually does

It reads a folder, identifies video files by extension, and — when the files
follow a sequential naming scheme — orders them and excludes anything that
doesn't match the pattern. It reads each clip's metadata and shows a
compatibility matrix across the set, so you can see up front whether the clips
can be stream-copied straight through, whether they're safe to remux to MP4, or
whether the mismatch means a re-encode. Stitching runs in whichever of those
modes fits, and it can export the result split at marker times or chapter from
VOD markers.

## What it deliberately doesn't do

There's no AI anywhere in it, and it isn't an editor. It won't rescue a set of
clips that were never contiguous, and it will not trim fade-in or fade-out
frames at the ends of clips — if your recording has them, they stay in. The
sequence detection is a convenience: a human still has to look
at the list and confirm the right files are in the right order before anything
gets written.

## Where it stands

Built with Electron, React and TypeScript over bundled FFmpeg binaries, packaged
with Electron Forge and Vite. v0.1.0 is tagged and released, and that's the last
version I worked on, because it did the job I built it for. It solved a real
problem exactly once and I will hopefully never need it again, which is the honest lifespan of a lot of good tooling.
