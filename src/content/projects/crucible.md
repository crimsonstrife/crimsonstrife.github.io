---
title: "Crucible SCM"
category: "web"
summary: "A self-hosted Git platform built for game teams — LFS with pluggable backends, file locking, engine-aware policies, and an Unreal Engine plugin that puts it in the editor's revision control menu."
order: 5
tags: ["Laravel 12", "PHP 8.3", "Git", "Git LFS", "Unreal Engine", "C++", "Tailwind CSS"]
links:
  repo: "https://github.com/crimsonstrife/crucible"
---

Git was designed for text, and game projects are mostly not text. A repository
full of multi-gigabyte `.uasset` files, textures and audio hits problems Git
never set out to solve: binaries don't merge, so two people editing the same
asset is a lost afternoon rather than a conflict marker, and a fresh clone can
mean tens of gigabytes nobody on that task needs. The usual answers are Perforce,
which costs real money and is its own world, or Git plus LFS plus a pile of
conventions everyone has to remember.

Crucible is a self-hosted platform that builds those conventions into the server.

## What it does

Repository hosting over SSH and HTTP, organisations with role-based access, and
pull requests with several merge strategies — the ordinary parts. The parts that
exist because of game work: Git LFS with pluggable storage backends (local disk
or S3), chunked and TUS uploads so multi-gigabyte assets survive the trip,
and file locking with automatic expiration, because a lock nobody released is
its own outage.

Above that sit engine-aware policies. Crucible knows what an Unreal, Unity or
Godot project looks like, applies the right LFS rules for each, and reads binary
metadata out of `.uasset` files so an asset is more than an opaque blob in a
diff. Sparse checkout profiles let someone clone the part of the project they
actually work in. Webhooks are HMAC-signed and carry commit status for CI, and
organisations get storage quotas.

## In the editor

Version control that lives in a browser is version control artists route around,
so there's a companion Unreal Engine plugin: a source control provider that
registers with UE's own Revision Control subsystem, the same place Perforce and
Git appear. It splits into three modules — a runtime, an editor module holding
settings and the HTTP client, and the provider implementation — and works by
combining local Git CLI operations with Crucible's own API for the server-side
concerns like locking and engine detection.

It is honestly still early: UE 5.6 is the target with 5.3–5.5 best-effort, the
connection flow, engine detection and policy application work, and check-out and
check-in are still stubs. Source is at
[ue-crucible-plugin](https://github.com/crimsonstrife/ue-crucible-plugin), MIT
licensed.

## Status

Laravel 12 on PHP 8.3 with Tailwind and Vite, backed by MySQL, MariaDB, Postgres
or SQLite and Redis or Valkey for cache and queues. Unlike Forge and Codex,
Crucible is proprietary rather than open source. In active development.
