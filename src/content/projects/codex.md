---
title: "Codex"
category: "web"
summary: "A Confluence-style documentation platform and the sister application to Forge — nested page trees, revision history, native diagrams and full-text search."
order: 4
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/codex-thumb.jpg"
  full: "../../assets/images/portfolio/codex.jpg"
tags: ["Laravel 12", "PHP 8.3", "Livewire 3", "Filament 4", "Laravel Scout", "Mermaid"]
links:
  repo: "https://github.com/crimsonstrife/codex"
---

Forge covers the work; Codex covers everything a team needs to write down around
it. It's a collaborative knowledge base built on the same stack, deliberately
kept as a separate application rather than another module — documentation has
different access patterns, a different editing model and a different audience
from issue tracking, and pretending otherwise is how wikis end up buried inside
project tools nobody reads.

## What's in it

Workspaces hold nested page trees with revision history, comments and wiki-style
linking between pages. Search runs through Laravel Scout, so it works against the
database out of the box and can be pointed at Meilisearch or Algolia when a
corpus outgrows that. Diagrams are native via Mermaid — written as text in the
page, versioned with it, rather than exported images that quietly go stale.
Role-based access control governs who sees what, and it can optionally
single-sign-on from Forge.

## Built with

Laravel 12 on PHP 8.3, Livewire 3, Filament 4 for administration, TinyMCE for
rich text, and Bootstrap 5 through Vite. MIT licensed, in active development.
