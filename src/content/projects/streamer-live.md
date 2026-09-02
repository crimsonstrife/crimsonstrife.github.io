---
title: "streamer.live"
category: "web"
summary: "A self-hosted Laravel CMS for streamers and creator communities — a page builder, Twitch and Discord automation, Fourthwall merch, analytics, and moderation in one platform."
track: "independent"
year: 2025
order: 5
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/streamer-live-logo.png"
  full: "../../assets/images/portfolio/streamer-live-logo.png"
gallery:
  title: "streamer.live in use"
  items:
    - image: "../../assets/images/portfolio/streamer-live-home.png"
      alt: "A streamer.live homepage combining an embedded Twitch stream and chat with blog posts and featured Fourthwall products"
      caption: "A creator homepage built with streamer.live: Twitch embeds, a subscriber promotion, recent posts, and synced merchandise share one layout."
    - image: "../../assets/images/portfolio/streamer-live-store.png"
      alt: "A streamer.live storefront with collection and product filters beside a grid of Fourthwall merchandise"
      caption: "The Fourthwall-powered storefront exposes collections, product metadata, filters, and external checkout without maintaining a second catalog."
tags: ["Laravel 11", "PHP", "Filament", "Livewire", "Alpine.js", "Twitch API", "Discord API", "Fourthwall API"]
links:
  repo: "https://github.com/crimsonstrife/streamer.live"
  docs: "https://getstreamer.live/"
---

Streamer communities tend to grow across a pile of disconnected services: a
homepage in one place, a blog in another, a Discord bot for announcements, a
Twitch embed, and a storefront whose catalog has to be maintained separately.
I built streamer.live as a self-hosted CMS that brings those pieces together
without taking control of the creator's site or audience.

## From stream to community

The Twitch integration monitors live status, title, category, and game, then
uses that state throughout the site. Pages can embed the stream and chat, and a
customizable banner tells visitors when the channel is live. The Discord bot
turns the same event into targeted announcements: each category can carry its
own message, destination channel, and role mention rather than sending every
notification to everyone.

## A CMS, not just a stream overlay

The page builder combines reusable, drag-and-drop content blocks with a WYSIWYG
editor, templates, slugs, and SEO metadata. A built-in blog adds posts,
categories, comments, and thread locking, so the creator's long-form content
lives beside the stream instead of becoming another external profile.

Fourthwall integration syncs merchandise into filterable collections and
product pages, then hands the shopper back to Fourthwall for cart and checkout.
The creator gets a storefront that matches the rest of the site without copying
product data between systems.

## Administration and moderation

Filament powers the administration dashboard, including granular permissions,
user and stream analytics, IP allow and block lists, account bans, and a
dedicated moderator panel. Akismet and StopForumSpam screen community
interactions so moderators can concentrate on people rather than automated
noise.

## Built with

Laravel 11 with Jetstream, Filament, Livewire, and Alpine.js on the application
side; Bootstrap, jQuery, and TinyMCE on the front end; and the Twitch, Discord,
and Fourthwall APIs for the integrations. The project is MIT licensed and
open-source. It remains under active development, and its public documentation
currently cautions against production use while the platform is changing
frequently.
