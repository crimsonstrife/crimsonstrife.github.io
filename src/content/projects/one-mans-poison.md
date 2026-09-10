---
title: "One Man's Poison"
category: "games"
summary: "A solo-developed third-person survival-horror game in Unreal Engine 5, turning a missing-person case into a focused vertical slice built around investigation, scarcity, and systemic threat."
track: "independent"
year: 2026
ongoing: true
order: 0
featured: true
caseStudy: true
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/one-mans-poison-hero.jpg"
  full: "../../assets/images/portfolio/one-mans-poison-hero.jpg"
tags: ["Unreal Engine", "C++", "Blueprint", "Gameplay Systems", "Editor Tools", "Animation Programming", "UI Engineering", "Online Services", "Multi-Platform", "Survival Horror", "Systems Design", "Game Design", "Technical Direction", "Project Management"]
links:
  live: "https://ompgame.com"
imageCredit:
  name: "Sherry Chen"
  role: "Character model, texture and render"
  url: "https://www.artstation.com/sherrrychen"
featureHighlights:
  - icon: "fa6-solid:diagram-project"
    title: "Three C++ modules"
    body: "Runtime, editor and input are separate modules. Gameplay rules live in C++; Blueprint carries the tuning layer that I do design work in."
  - icon: "fa6-solid:table-cells-large"
    title: "A real spatial inventory"
    body: "Grid placement, rotation and drag-and-drop, presented as a layered 3D attaché case composited from render targets."
  - icon: "fa6-solid:screwdriver-wrench"
    title: "Editor tooling"
    body: "A custom Blueprint compiler node, asset factories, a thumbnail renderer, and two in-house editor plugins built to speed up repetitive work."
  - icon: "fa6-solid:person-running"
    title: "Animation programming"
    body: "A custom anim instance driving locomotion and overlay states, with bespoke notifies for footsteps, camera shake and early blend-out."
  - icon: "fa6-solid:gamepad"
    title: "Input and platform"
    body: "A dedicated input module with per-platform controller data across eight device families, plus the work to run the game on Steam Deck."
  - icon: "fa6-solid:plug"
    title: "Integrations"
    body: "FMOD audio, CrowdControl, and a reusable Twitch channel-points plugin written for the project and to be released separately."
---

One Man's Poison starts as a missing-person case. Darla Brant hires private
detective Jake Dubrowski to find her younger sister, Jess; the trail ends at the
supposedly abandoned Fountain Vale Mental Hospital. Jake enters as an
investigator and wakes as a prisoner, with the building beginning to disobey its
own floor plan around him.

It's a third-person survival-horror game in active development at
CrimsonStrife Games for PC and Linux. It uses the title and certain characters
from the short film *One Man's Poison*, created by James Cotton through Wolf359
Productions, with permission. The setting, game systems, expanded cast, and the
shape of this particular story have been developed for the game.

This is a development case study, not a postmortem for something finished. The
useful story is how a large horror-game premise got cut down into something one
person can actually build, how the systems hold that experience up, and where
the project honestly stands right now.

## My role is bigger than the code

I'm the project lead, the designer, and, so far, the only developer. All the
gameplay programming in C++ and Blueprint is mine — inventory, interaction,
combat, animation, input, and the framework underneath them. I also own the
technical architecture, game design, production planning, art direction, platform
work, and the public website.

Bringing in specialists is how the work gets done without pretending one person
is every discipline. Composition and audio engineering, concept art, casting and voice
direction, and character modeling and texturing are collaborative work. My part
is to turn the game's requirements into useful briefs, review work against the
art and story bibles, keep credits attached to assets, and schedule each
deliverable around what the build can actually use.

The Jake model, texture, and hero render on this page are by
[Sherry Chen](https://www.artstation.com/sherrrychen). I led the character
design and handled rigging.

## The first design problem was making it finishable

The full design covers a campaign, a changing asylum, several kinds of enemies,
branching outcomes, a persistent hunter, an adaptive Director, accessibility
settings, and more. That's an exciting list and a terrible first milestone for a team of one.

The working target is now a public vertical slice with a deliberate boundary:
introduce Jake, establish Darla's case, get the player into Fountain Vale, and
prove one complete horror-and-puzzle loop. It has to demonstrate the controls,
investigation, limited combat, resource pressure, stealth, an anchor room, and
the first enemy pressure. It doesn't have to summarize the whole campaign or
spend the story's biggest reveal just to make a demo look complete.

That boundary changed production from "make the game" into a sequence I can
test: grounded opening, asylum entry, safe room, threat area, puzzle, shortcut,
return. Every feature now has to earn a place in that loop.

## Five pillars keep the features pointed the same way

**Detective horror.** Jake is working a case, not just looking for an exit.
Clues, documents, environmental details, and puzzles need a reason to exist in
the world and should reframe what the player thinks happened there.

**Scarcity.** Ammunition, healing, weapon durability, and inventory space are
competing resources. Combat is usually an option, but not always a good one, and the player has to be
able to decide on the fly.

**Systemic pressure.** The long-term goal is a hunter that exists across rooms
and rest phases instead of teleporting in for a scripted scare. If the player can learn how it behaves, anticipation does more work than a jump
cut.

**A world with rules.** Fountain Vale can loop, shift, and absorb Jake's
memories, but the instability still needs an internal logic. The premise that the Asylum is playing with reality connects the
architecture, interface, save points, hallucinations, and progression instead
of excusing arbitrary weirdness.

**Readable horror.** Tension should come from a challenging decision made with
understandable information. Routes need landmarks, puzzles need findable clues,
enemies need tells, and accessibility options should remove friction without
removing the horror.

## How the project is put together

The game builds as three C++ modules rather than one. `OneMansPoison` holds the
runtime — characters, components, subsystems, AI, animation. `OneMansPoisonInput`
is separate because input turned out to need its own lifetime and its own
platform-specific data. `OneMansPoisonEditor` holds everything that only exists
while the editor is open, so none of it can leak into a shipping build.

That split is the reason the rest of this page can be honest about where things
stand. Systems that share a module tend to grow into each other; systems that
have to talk across a module boundary need an interface, and an interface is
something I can change without re-testing the world.

`OneMansPoisonInput` specifically came about to help detect controller types for proper UI handling, and handling controller type defaults by platform.

## The loop is a set of connected systems

The core loop starts in a secure anchor room. The player leaves to investigate,
collect evidence and supplies, solve a local problem, and decide whether to
avoid or spend resources on a threat. A shortcut or story change opens the way
back. The further the player pushes before returning, the more the Director can
respond to time, noise, progress, and the resources still in reserve.

That means these aren't independent portfolio features. Inventory capacity
changes exploration. Exploration changes exposure. Noise changes enemy
attention. A shortcut changes the value of pressing forward. The save room
stores the consequences of all of it.

### The inventory is the clearest implemented example

The inventory began with my memory of *Resident Evil 4*: a "Tetris" case full
of irregular shapes. That memory was wrong in a useful way. Its items are
rectangles, so a footprint only needs a top-left root slot, width, height, and
orientation. Collision becomes a predictable bounds check instead of a library
of arbitrary polyomino masks.

The working prototype supports grid placement, rotation, and drag-and-drop. The
visual problem was harder: the case should feel like a physical object, but a
separate 3D inventory scene and a rendered actor for every item would be too
expensive for what the feature needs.

I use Unreal Render Targets and Scene Capture actors to capture the case in two
layers. The UI composes them as back-of-case render, interactive slot grid, then
front-of-case render. Items remain UI images for now. The result sells depth
without spawning and positioning a second set of item actors every time the
inventory opens. I wrote more about the tradeoff in
[What's in an Inventory?](https://ompgame.com/blog/whats-in-an-inventory/).

The widgets don't poll the inventory. The component publishes a change event for
every operation that can move an item — added, removed, amount changed, moved,
resized, equipped, loaded — and the UI subscribes to the ones it cares about.
That keeps the grid, the shortcut bar, and the equipment panel consistent without
any of them knowing about each other, and it means a system that isn't UI at all
(a save or load for instance) updates the interface for free.

The system is in progress rather than done. Placement and presentation work;
item consumption and the full weapon/ammunition connection are the next part of
the same feature.

### Animation is programming, not just assets

The character runs on a custom anim instance that resolves locomotion, aiming,
overlay states, and the transitions between them, backed by a struct library that
keeps the tuning data out of the graph. Alongside it are bespoke notifies and
notify states — footsteps that pick a surface, camera shake, grounded entry,
early blend-out, movement actions, and overlay override — so animation events
drive gameplay rather than being decorated by it.

Much of this is likely to be reworked since the move to UE5 from UE4 changed some things, but the current system as implemented does function.

Worth separating from the assets: the animation *content* currently in the build
is placeholder and will be replaced. The systems that play it are not.

### Input got its own module for a reason

Controller handling is a separate module carrying per-device data for PlayStation
4 and 5, Xbox One and Series, Switch Pro, Stadia, Steam Controller and Steam
Deck, plus Steam Input helpers and identification logic that works out what is
actually plugged in. Prompts, remapping, and platform quirks all key off that.

This started as the work needed to support different controller icons and layouts as well as handling controller type defaults by platform. It turned
into the layer that makes any future platform a data problem rather than a code
problem.

### Editor tooling is part of the build

Some of the work that saves the most time never ships. The editor module carries
a custom Blueprint compiler node that lowers into two set-by-reference nodes so a
common "remember the old value, then set the new one" pattern stops being four
nodes of boilerplate, an asset factory and type actions for inventory items, a
custom thumbnail renderer so those items are identifiable in the content browser,
comment tooling for Blueprint graphs, and a skeleton virtual-bone utility.

Two of those tools grew past the project and became plugins in their own right:
an in-house developer toolkit that is still early phases, and
[Physics Placement Assist](https://github.com/crimsonstrife/ue-physics-placement-assist),
an editor-only plugin that drops and settles selected actors using the editor
world's physics scene, restoring every temporary flag before applying the result
so a whole batch reverts with one undo.

### The Director and hunter are architecture, not a finished claim

The Director is designed as a central pacing system. Its inputs include player
progress, resources, noise, and time in an area; its outputs can influence
hunter pressure, encounters, ambience, and music intensity. The hunter side has
its world and director subsystems, an anchor-aware AI component, an encounter
response component, emergence points, and custom behavior-tree tasks, services,
and decorators for the specific decisions a generic tree handles badly.

What isn't finished is the behavior those parts are meant to add up to: a threat
that persists across rooms and rest phases with real pursuit, search, withdrawal
and repositioning states, auditory foreshadowing, and a rule for respecting the
anchor-room boundary. The contract between the systems is built and tracked, but nothing has been implemented yet. I'd
rather show that than pass a design document off as shipped AI.

## C++ owns the rules; Blueprint exposes the tuning

The technical direction is a C++ core with a Blueprint layer for values a
designer needs to change while playing: thresholds, timings, resource values,
and presentation hooks. That split keeps the rules testable and discoverable in code without turning
every balance pass into a compile cycle.

The same boundary helps with integrations. FMOD owns the audio authoring
workflow, while the game provides state for intensity and events. I've also built
[a reusable Twitch viewer-interaction plugin](/projects/ue-twitch-native/), integrated CrowdControl,
and done the platform work required to run the game on Steam Deck. Stream
features stay isolated from the core loop; a community feature should never be
able to destabilize the single-player game it's decorating.

## Accessibility has to go in early or not at all

The accessibility plan covers input remapping, toggle-versus-hold actions, text
and UI scaling, subtitle and closed-caption support, independent audio levels,
motion and flashing reduction, high-contrast and color-vision options, puzzle
hints, navigation assistance, and difficulty controls for resources and enemy
behavior.

Most of that menu is planned work, not a finished suite. Writing it into the
technical design now still matters: a puzzle framework needs an optional hint
layer before dozens of puzzles depend on it, important sounds need visual-event
hooks before the audio pass is final, and input actions need to be remappable
before they spread through one-off Blueprint graphs.

The companion site follows the same principle today with visible content
warnings, reduced-motion controls, and privacy choices that leave analytics off
until a visitor opts in.

## Production is a system too

The game is tracked through linked milestones, issues, discipline documents,
asset records, and risk notes in Notion. The design document defines the player
experience; the technical document defines how the systems divide; the art and
story bibles keep collaborators working from the same canon. Unreal-style asset
naming and explicit credit metadata are deliberately boring safeguards against a
multi-year project turning into a folder of files only I understand.

Publicly, [ompgame.com](https://ompgame.com) turns part of that production work
into a devlog, press kit, release log, and read-only roadmap. I built that site as its own Astro
application and wrote a separate
[case study for the web platform](/projects/ompgame-com/) rather than treating it
as a footnote to the game.

## Where it stands

One Man's Poison is in active development, focused on the core loop and the
vertical slice. The spatial inventory is playable and still being connected to
item use, weapons, and ammunition. The broader Director, hunter, settings, and
accessibility work is specified and scheduled around that slice. There's no release date yet; the current platform target is PC and Linux.

The most useful thing to come out of it so far isn't a count of systems. It's a
clearer way to decide which one deserves to exist next: prove the missing-person
case, the resource decisions, and the trip from safety into danger and back. If a
feature doesn't make that loop stronger, it can wait.