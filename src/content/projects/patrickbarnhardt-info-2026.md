---
title: "patrickbarnhardt.info (2026 rebuild)"
category: "web"
summary: "The current version of this portfolio: an Astro rebuild that turns years of static HTML, project media and resumé history into structured, maintainable content."
track: "independent"
year: 2026
order: 0
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/patrickbarnhardt-info-2026.png"
  full: "../../assets/images/portfolio/patrickbarnhardt-info-2026.png"
tags: ["Astro 7", "TypeScript", "Responsive Design", "Accessibility", "GitHub Pages", "Cloudflare Workers"]
links:
  live: "https://patrickbarnhardt.info/"
  repo: "https://github.com/crimsonstrife/crimsonstrife.github.io"
---

The previous version of this portfolio was a static HTML and CSS site that had
been running since around 2017. This rebuild keeps that version in the
repository history, but replaces the live site with an Astro application built
around content that can grow without hand-editing every page.

## What changed

Projects, work history, certifications and the rest of the resumé now live in
validated Markdown and JSON collections. Astro turns those sources into a
fully static site, including individual project pages, a printable resumé, an
RSS feed and social-sharing metadata. The project archive can be filtered by
discipline and context without hiding the older work that documents how the
portfolio evolved.

GitHub activity is fetched at build time so visitors do not spend an API request
or wait on client-side loading. The contact forms post to a small Cloudflare
Worker with Turnstile validation, while the site itself remains deployed as
static files through GitHub Pages.

## Built with

Astro 7, TypeScript, semantic HTML and custom CSS, with GitHub Actions handling
checks and deployment. The source repository also retains the old static site
in its history instead of erasing it.
