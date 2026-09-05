---
title: "CEO Juice API Connector"
category: "web"
summary: "A WordPress plugin that lifts the CEO Juice integration out of one company's theme and makes it configurable — settings, credential storage, and a cache so a dealer's Net Promoter Score doesn't cost an API call on every page view."
track: "professional"
year: 2022
yearApprox: true
order: 9
tags: ["WordPress", "PHP", "Plugin", "REST API", "PHPFastCache"]
links:
  repo: "https://github.com/crimsonstrife/wp-ceojuice"
---

The Net Promoter Score section I built for the Modern Impressions site works, but
it is welded to that site. It reads its credentials through the theme's own
options library, it lives at a hardcoded path inside the theme directory, and it
calls the API on every single page load. Move it to another dealer's WordPress
install and none of that survives.

CEO Juice serves most of the large copier dealers in North America, so "another
dealer's WordPress install" is not a hypothetical. This is the attempt to make
the thing portable.

## What it does

It's a plugin, so it's theme-independent. The settings page is built on the
WordPress Settings API and splits into three sections: API credentials, feature
toggles for the NPS and testimonials endpoints, and cache configuration. Each
sub-section gets its own admin menu entry, plus a how-to tab, because a settings
screen nobody can find is the same as no settings screen.

The caching is the part that mattered most. The theme version called the API
every request; here PHPFastCache sits in front of it with a configurable duration
and a unit selector, so you can type "4" and pick hours rather than doing
arithmetic in your head. The value is clamped between a minute and a day — long
enough to be worth caching, short enough that a score is never badly stale.

It fails loudly rather than quietly. If the customer number or API code is
missing you get a dismissible admin notice. If caching is on and the server is
below PHP 7.3, which PHPFastCache needs, you get a different one and the plugin
carries on with caching disabled instead of fataling.

## What it doesn't do

The `[ceo_nps_score]` shortcode is registered, accepts its `meter_style` and
`display_type` attributes, and then does nothing — the rendering body was never
written. I left Modern Impressions in March 2023 and the plugin stopped at the
scaffolding.

So this is honestly half a project: a complete configuration and caching layer
with no front end attached to it. The working implementation is still the theme
integration it was extracted from, which is a fine outcome for the company and an
unfinished one for the plugin. I'm including it because the settings and caching
work stands on its own, and because a portfolio that only contains finished
things is not a portfolio, it's a highlight reel.

GPL-3.0, like the WordPress code it sits on top of.
