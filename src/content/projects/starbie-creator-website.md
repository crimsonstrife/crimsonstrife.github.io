---
title: "Starbie Creator Website"
category: "web"
summary: "A multi-page Astro site for VTuber, artist, composer, and sound designer Starbie, translating her colorful celestial brand into an accessible, creator-maintainable home for her profile, work, links, support, and credits."
year: 2026
track: "independent"
ongoing: true
order: 1
caseStudy: true
tags:
  - "Astro"
  - "TypeScript"
  - "Content Collections"
  - "Responsive Design"
  - "Accessibility"
  - "Web Design"
  - "Information Architecture"
  - "GitHub Pages"
links:
  live: "https://patrickbarnhardt.info/shiningstarbie-site/"
  repo: "https://github.com/crimsonstrife/shiningstarbie-site"
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/starbie-site-home.png"
  full: "../../assets/images/portfolio/starbie-site-home.png"
gallery:
  title: "Custom 404 pages to keep the branding consistent."
  items:
    - image: "../../assets/images/portfolio/starbie-site-404.png"
      alt: "Starbie website 404 page with the number 404 drawn as a colorful constellation, the heading Lost in the stars, and a Nox character illustration"
      caption: "The custom 404 page carries the celestial theme through its constellation lettering, route map, rotating message, and optional character art."
imageCredit:
  name: "Starbie and credited collaborators"
  role: "Logo, character art, and brand graphics shown in the site"
  url: "https://patrickbarnhardt.info/shiningstarbie-site/credits/"
---

I designed and built this multi-page promotional site for **Starbie**, a VTuber, artist, music composer, and sound designer. It expands her earlier single-page presence into a more complete home for her identity, creative work, community links, support options, and artist credits.

Depending on when you're viewing this, the final domain may not be live, if so then the public GitHub Pages preview shows the site's working structure and primary content while the final domain and some of the work archive are still in progress. Starbie's existing logo, character art, and illustrations remain her property and are credited to her and the respective artists; my role covers the site's design and implementation, some art assets will be replaced.

## Translating the brand into a site

The visual system grew directly from Starbie's artwork. I sampled two related color palettes: soft sticker-like pastels for backgrounds and decoration, and deeper versions of the logo spectrum for links, controls, and other elements that need stronger contrast. A dark ink color keeps body text readable across every pastel band.

Rather than placing the entire site in a conventional grid of cards, the layout uses full-width color bands, cloud-shaped dividers, tiny stars, and loops. Rounded display type carries the friendly character of the logo while a quieter sans serif keeps longer copy comfortable to read. The result feels playful without letting decoration compete with the content.

The same system adapts across desktop and mobile layouts. Navigation condenses for narrow screens, sections reflow around their content, and decorative animation respects the user's reduced-motion preference by swapping the falling-star effect for a still image.

## A site Starbie can maintain

The site is built with **Astro and TypeScript**, but routine updates do not require editing layout code. Profile details, social links, support options, credits, and work entries live in Markdown and JSON files that Starbie can edit through GitHub's web interface. A companion editing guide explains where each type of content belongs and how to publish a change.

Astro content collections and Zod schemas validate repeatable content during the build. That gives the flexibility of simple text files while catching missing fields, invalid link types, and malformed work entries before they reach the live site. Presentation stays in reusable components, so additions inherit the established spacing, typography, responsive behavior, and color treatment automatically.

## Profile, work, support, and attribution

Dedicated Home, About, Blog, Works, Links, Support, and Credits routes give each kind of information room to breathe. The work system can present illustration, music, and sound-design projects with images or embedded media, while the links and support sections gather Starbie's community destinations in one place.

Attribution was treated as part of the content model rather than an afterthought. Credits can travel with individual images and work entries, and the dedicated credits page collects the people and tools involved in the broader identity. That keeps ownership clear even as media is reused throughout the site.

## Making even errors feel intentional

The custom 404 page turns a dead end into a small extension of the brand. Its oversized “404” is drawn as a constellation, a star path maps the site's main destinations, and a rotating set of short messages can pair with an optional illustration. A static no-JavaScript fallback keeps the page understandable, and only the selected illustration is downloaded.

## Where it stands

The site is currently statically generated and deployed from its public repository through GitHub Actions and GitHub Pages. Its component system, main routes, responsive shell, editing workflow, and initial content are online at the temporary project URL. Again, depending on when you're viewing this, the remaining work is primarily editorial—expanding the work archive and completing the eventual custom-domain launch—so the portfolio entry is marked as an ongoing project at time of writing.
