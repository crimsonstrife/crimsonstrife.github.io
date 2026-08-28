---
title: "The site, rebuilt"
description: "A short note on replacing eight years of hand-written HTML with something I can actually update."
pubDate: 2026-08-28
tags: ["meta", "astro"]
draft: true
---

Replace this with your first real post, or delete the file.

While `draft: true` is set, the post shows up when you run `npm run dev` but is
left out of the production build — so nothing ships until you're ready.

## Adding a post

Drop a Markdown file in `src/content/blog/`. The file name becomes the URL, so
`unreal-material-notes.md` publishes at `/blog/unreal-material-notes/`.

The frontmatter above is the whole contract:

- `title` and `description` are required — the description is what shows on the
  index page, in the RSS feed, and in link previews.
- `pubDate` sorts the index and stamps the feed.
- `tags` are optional and generate their own pages at `/blog/tags/<tag>/`.
- `heroImage` is optional; point it at a file next to the post and it gets
  optimized like everything else.

Anything you'd write in Markdown works here — headings, lists, `inline code`,
fenced code blocks, blockquotes, tables and images.
