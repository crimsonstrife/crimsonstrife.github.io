---
title: "UE Twitch Native"
category: "tools"
summary: "An in-progress Unreal Engine 5 runtime plugin that turns Twitch Channel Point redemptions into Blueprint-ready gameplay events and keeps game-owned rewards synchronized through the Helix API."
track: "independent"
year: 2026
order: 1
tags: ["Unreal Engine 5", "C++", "Blueprint", "Twitch SDK", "Twitch Helix API", "Channel Points", "UMG"]
links:
  repo: "https://github.com/crimsonstrife/ue-twitch-native"
---

UE Twitch Native began as the viewer-interaction layer for
[One Man's Poison](/projects/one-mans-poison/). The immediate goal is to let a
streamer connect the game to Twitch and map custom Channel Point rewards to
events inside Unreal. The longer-term goal is a broader set of audience tools —
including polls and choices that can influence what happens in the game —
without coupling those features directly to the game's core systems.

The plugin is deliberately reusable rather than a collection of
*One Man's Poison*-specific Blueprint graphs. It packages the Twitch connection,
authentication, reward definitions and incoming events behind an Unreal runtime
module, leaving each game to decide what a redemption actually does.

## From a Twitch redemption to a gameplay event

A `UGameInstanceSubsystem` owns the integration for the lifetime of the game
session. It wraps the Twitch SDK's authentication state, retrieves the connected
broadcaster's identity and starts the custom-reward event stream after login.
The plugin mirrors the SDK's status and event data in its own Blueprint types,
so downstream game code does not need to depend directly on the SDK headers.

An incoming redemption carries the redemption and reward IDs, the viewer's
name, reward title, optional text input and point cost. The subsystem marshals
that callback back to Unreal's game thread, filters duplicate redemption IDs and
broadcasts a Blueprint-assignable event. Known rewards are routed through a
stable `RewardKey`; title matching remains a fallback rather than becoming the
gameplay contract.

That boundary is important. A reward can be renamed on Twitch without forcing
every listener in the game to change, and the same integration can drive very
different effects in another project.

## Reward packs keep configuration out of code

Custom rewards are described in an Unreal Primary Data Asset. Each definition
contains its stable key, title, prompt, cost, enabled state, optional viewer
input, per-stream and per-user limits, global cooldown, color and request-queue
behavior. An optional title prefix provides a namespace such as `OMP:` so the
plugin only manages rewards that belong to that game.

Synchronization uses Twitch's Helix API. The plugin reads the broadcaster's
manageable rewards, updates matching definitions, creates missing ones and
removes obsolete rewards inside its own prefix. Requests run sequentially to
avoid a burst of API calls, then the refreshed reward IDs are mapped back to the
game's keys. Redemptions that enter Twitch's request queue can also be marked as
fulfilled or canceled from Unreal.

## Authentication has a presentation boundary too

The connection flow uses Twitch's device-code authentication and tracks logged
out, loading, waiting-for-code and logged-in states. It can open the verification
page, poll for completion, restore an existing session and optionally connect on
startup.

An abstract UMG widget exposes login, logout and copy-code actions plus
Blueprint events for each state. It provides the behavior but no imposed visual
style, so a game can build an authentication screen that belongs in its own UI.
Project settings hold the default OAuth scopes, reward pack and automatic
connection/synchronization choices.

## Where it stands

The public repository currently contains a beta runtime plugin with the complete
authentication-to-redemption path and the reward synchronization layer. Custom
Channel Point rewards are the implemented interaction surface today. Polls and
other ways for viewers to affect gameplay are the next stage of the design, not
features this wrapper exposes yet.

For *One Man's Poison*, the value is more than one stream gimmick. This creates
a controlled boundary between an external live service and a single-player
horror game: Twitch can request an effect, while the game remains responsible
for deciding whether, when and how that effect is safe to apply.
