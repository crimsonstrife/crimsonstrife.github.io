---
title: "Physics Placement Assist"
category: "tools"
summary: "An in-progress Unreal Engine 5.6 editor plugin that turns manual prop placement into an undoable physics-assisted workflow using the editor world's Chaos scene."
order: 60
tags: ["Unreal Engine 5.6", "C++", "Chaos Physics", "Editor Tooling", "Slate", "V-HACD"]
links:
  repo: "https://github.com/crimsonstrife/ue-physics-placement-assist"
---

Placing a pile of props by hand is slow in exactly the wrong way. Every object
has to be nudged out of an overlap, rotated until it looks natural, and checked
again from another angle. Physics Placement Assist keeps the normal Unreal
Editor selection and transform workflow, but lets Chaos do that last part.

## The editor workflow

With assist mode enabled, actors dragged with the standard gizmo collide with
the level instead of passing through it. Releasing the gizmo hands the selection
to a settle simulation so gravity, contact and friction can finish the
placement. A separate command drops the current selection on demand, and arrange
commands can level a group, randomize its yaw, clump it or spread it before the
same settle pass.

The result remains editor work. The plugin adds no runtime components and ships
nothing in a packaged game. Commands live in the Tools menu, can be rebound in
Editor Preferences, and expose per-user tuning for simulation time, fixed
timestep, gravity, collision and settle thresholds.

## Preserving the scene around the simulation

Before a drop, the plugin saves each actor's mobility, collision mode, gravity,
CCD and transform. It then advances the editor world's Chaos solver in fixed
steps until the bodies remain below the linear and angular velocity thresholds,
or until the time budget is exhausted. Temporary flags are restored before the
settled transforms are applied inside one editor transaction, so a single undo
reverts the complete operation.

Nearby render-only scenery can participate through transient scene proxies that
borrow already-cooked triangle meshes without modifying the user's components or
assets. For props that lack usable simple collision, the plugin also exposes
bulk convex-hull generation through Unreal's V-HACD path.

## Where it stands

Version 0.1 is an editor-only beta for Unreal Engine 5.6. Static meshes with
usable collision are the primary path today; instanced meshes, foliage, spline
meshes and skeletal meshes remain outside the supported set. A demonstration
GIF is still to come, but the repository documents the current commands,
settings, limitations and roadmap.
