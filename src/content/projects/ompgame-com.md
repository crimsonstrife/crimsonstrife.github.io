---
title: "ompgame.com"
category: "web"
summary: "The marketing and press site for One Man's Poison. It's an Astro build with a press kit, devlog, live roadmap and a feedback system."
track: "independent"
year: 2026
order: 1
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/ompgame-site-hero.jpg"
  full: "../../assets/images/portfolio/ompgame-site-hero.jpg"
tags: ["Astro", "Preact", "GSAP", "Content Collections", "Web Design"]
links:
  live: "https://ompgame.com"
---

The public site for [One Man's Poison](/projects/one-mans-poison/) — design and
build, all mine. It carries the game's marketing pages, but most of it is closer
to an application than a brochure.

## What's in it

**A press kit that maintains itself.** Logos, concept art, character sheets, and
renders are described in one JSON file with credits, dimensions, and thumbnails,
and the gallery, download links, and attributions all generate from it. Adding an
asset is a data edit, I just push some files and edit some JSON.

**A live roadmap.** The board pulls from Notion or Trello through their APIs, so
what the public sees tracks the actual project board rather than a page I have
to remember to update.

**A feedback system with passwordless sign-in.** Email-link auth, submission and
posting flows — a real authenticated feature on a statically-hosted site. 
This is currently not active, but will release alongside the game.

**Story and cast as structured content.** Characters, subjects, cast, and team are
content collections with their own generated routes, so the story section grows
by adding files, just pop a new .md file in and build.

**The details that usually get skipped.** An accessibility dock, a soundtrack
player, a content-warnings page, and a full policy suite — EULA, privacy, terms,
cookies, and content guidelines.

## Built with

Astro 6 with Preact islands for the interactive pieces, GSAP for motion, Embla
for carousels, and Markdown content collections throughout. Deployed to GitHub
Pages.
