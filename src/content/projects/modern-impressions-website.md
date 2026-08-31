---
title: "Modern Impressions Website"
category: "web"
summary: "Five years as webmaster for a Charlotte office-technology dealer, building onto a bought theme: a live Net Promoter Score dashboard wired to a third-party API, a branching support triage flow, and a knowledge base I wrote most of."
order: 8
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/modern-impressions-nps-thumb.jpg"
  full: "../../assets/images/portfolio/modern-impressions-nps.jpg"
tags: ["WordPress", "PHP", "Vue.js", "D3.js", "Bootstrap", "WooCommerce", "Authorize.Net", "Less"]
links:
  repo: "https://github.com/ModernImpressions/MI-WPtheme"
  live: "https://modernimpressions.com"
---

Modern Impressions sells and services office technology across the Carolinas.
I was hired as an IT support technician in 2018 and picked up webmaster duties
along the way, which in a company that size means you are the web team.

The starting point was not mine. The theme was bought — `style.css` still credits
Triad Web Design Service — and my job was never to redesign it from zero but to
keep it running and make it do things it couldn't. Nearly everything below is
work built onto that base rather than a site of my own authorship, and I'd
rather say so than let a screenshot imply otherwise.

## The Net Promoter Score section

Modern Impressions subscribes to CEO Juice, which automates post-service follow-up
surveys for copier dealers and scores the results. The company wanted that on the
homepage — not a static number someone remembers to edit, but the real one.

So the section pulls it. A theme part calls the CEO Juice API for the company's
NPS record and the benchmark set that comes with it, sorts our own score out from
the reference companies, and renders two things: a D3 half-circle gauge with a
needle, and a ladder of `<progress>` bars putting the dealer's score next to
Costco, Ritz Carlton, USAA, Amazon and the B2B average. A second call pulls the
awards widget. A companion part hits the survey-comments endpoint, filters out
anything over 320 characters so the layout doesn't break, and feeds the survivors
into an Owl Carousel as testimonials with the customer's name and response date.

The credentials and the comment count live in theme options, so nobody needs to
touch PHP to change them, and each section has an off switch for when the API is
having a bad day.

## The support center

The support page opens with a question — *how can we help you today?* — and five
answers: technical issue, account change, order supplies, submit a meter reading,
pay an invoice. Each one branches. It's a Vue Flow Form questionnaire whose whole
purpose is to get someone to the right page in two clicks instead of making them
read a navigation menu written by people who already know where everything is.
Below it, a knowledge base search box and a "Most Helpful Articles" list built
from a `WP_Query` ordered on the helpful-vote meta key, so the list ranks itself.

## The knowledge base

I wrote the templates and most of the articles. The templates override BasePress
to add a table of contents, breadcrumbs, previous/next article navigation, and a
metadata strip driven by custom fields — difficulty, which printer brand the
article is about, and which platform the reader is on, so a Mac driver guide and
a Chromebook guide can share a category without confusing anyone.

## The rest of it

A product catalog over WooCommerce templates, an invoice payment page against
Authorize.Net, a staff directory split by department, a driver and firmware
download page, and the usual long tail of a site that is somebody's actual job.

## What's still standing

All of it, more or less. As of today the homepage still shows the gauge — 98 —
above the same benchmark ladder, the testimonial carousel is still turning over
live survey comments, and the knowledge base articles still carry the metadata
strip and the previous/next navigation I built for them. The design has drifted
in places and other people have clearly been in there since, which is how it
should be. The repository here is the theme as it stood when I handed it over
in March 2023.
