---
role: "Game Systems Engineer"
headline: "Game systems engineering"
blurb: "Gameplay, engine tooling and the service work around them — C++, Unreal, and shipping tools other people use."
order: 2
description: >-
  Patrick Barnhardt's game engineering work, grouped by the domains postings
  name: gameplay systems in Unreal and C++, editor tooling, live service
  integration, UI, and web development — with the projects behind each and an
  honest list of gaps.
intro: >-
  Postings for this role usually ask for depth in a few named engineering
  domains rather than all of them. These are the five I can show work for, with
  the projects behind each. Most of it is public — open repos, or something
  deployed you can go and use, some are still work-in-progress.
domains:
  - label: "Gameplay"
    note: >-
      A C++ core with a Blueprint layer over it, so designers can tune
      behaviour without a compile cycle. The write-up covers the systems and
      the scoping decisions, including the ones that turned out wrong. 
      Code is not public, but I would be happy to give a demonstration.
    projects: ["one-mans-poison"]
  - label: "Tools"
    note: >-
      Editor tooling in C++ and Slate, plus an engine plugin that adds a
      third-party service to a native editor menu. Tools work for other people
      to use, not scripts I ran once.
    projects: ["physics-placement-assist", "crucible", "ue-twitch-native"]
  - label: "Online services"
    note: >-
      Live third-party APIs consumed from inside the engine and from web
      services — auth, event subscriptions, and the failure handling.
    projects: ["ue-twitch-native", "streamer-live"]
  - label: "UI"
    note: >-
      Slate and UMG on the engine side; on the web side a sandboxed live-preview
      editor. The Taniti project is the research end of the same discipline —
      wireframes and usability testing rather than implementation.
    projects: ["ue-twitch-native", "oshi-lab", "hjp1-taniti-tourism"]
  - label: "Web development"
    note: >-
      Laravel applications and static-site work, including passwordless
      email-link auth and a documentation platform with full-text search.
    projects: ["forge", "codex", "ompgame-com", "patrickbarnhardt-info-2026"]
gaps:
  - >-
    I have not shipped a commercial game. One Man's Poison is in development
    and unreleased; the project before it was cancelled before release due to cost.
    The closest things I have shipped to real users are mods, plugins and web
    applications.
  - >-
    I have not shipped a C# project. What I have is a C# course during my
    Associate's Degree which was game-focused, Java through my Bachelor's Degree (mostly for Android) and since, regular Apex — which is a
    strongly typed C-family language and reads much like Java — and Unreal's own
    build tooling, which is C# though I don't generally have to change much there. 
    Close enough that I would not be starting from zero and could get up-to-speed quickly, but I am not comfortable calling myself a C# developer.
  - >-
    I have had the developer title since 2023. Before that the work was still
    programming without the title on it: VBScript automation for a document
    capture pipeline and custom PHP on a company site I maintained but did not
    build. If a posting means five years under a developer title specifically,
    I am short of it; if it means five years writing code that people depended
    on, I am not.
---

Everything above links to the archive, where each project lists the
technologies it used. The tags are links too, so if you want every project that
touched a particular thing, that is one click from any of them.

If it is easier to skim on paper, the [printable resumé](/resume) is two pages.
