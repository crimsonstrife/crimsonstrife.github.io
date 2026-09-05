---
title: "TalentFlow (RYM2)"
category: "web"
summary: "My WGU software capstone — a college recruitment platform written in plain PHP with no framework, because the assignment wouldn't let me reach for one."
track: "education"
year: 2024
order: 7
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/rym2-talentflow-thumb.jpg"
  full: "../../assets/images/portfolio/rym2-talentflow.jpg"
tags: ["PHP", "MySQL", "Bootstrap", "jQuery", "Chart.js", "Leaflet", "PHPUnit"]
links:
  repo: "https://github.com/crimsonstrife/rym2"
---

The brief came from a real problem at a real company. When staff visit colleges
to recruit interns, they collect student details on paper, across several people,
and someone reconciles it all afterwards. That's a transcription error waiting to
happen, and because the data lands nowhere useful, follow-up is inconsistent —
the company loses track of people who were interested enough to write their name
down.

TalentFlow is the system that replaces the clipboard: a public form students fill
in at the event, and a secured dashboard where staff manage what comes back.

## The constraint that shaped it

Everything you'd normally lean on, I couldn't. The application had no access to
company systems, so no single sign-on and no existing database to build against —
authentication, sessions and roles all had to be mine. And no PHP framework: no
router, no ORM, no migrations, no auth scaffolding, no validation layer. Just PHP
8.1 and MySQL.

So all of that got written. Request routing, a login system with role-based
permissions, database access, form handling and validation, file uploads, an
activity log, email confirmation over SMTP. Front-end libraries were allowed —
Bootstrap and jQuery for the interface, Chart.js for graphs, Leaflet for mapping
school locations — but nothing that would do the server-side thinking for me.

It's the single most useful thing the degree made me do. Frameworks solve real
problems, and you understand which problems a lot better after a few months of
solving them yourself, badly, at three in the morning.

## What it does

Students register without an account — the form is mobile-responsive because it's
filled in standing up at a table. Staff sign in to manage students, schools,
events, jobs and internships, and the degree taxonomy behind them (programs,
majors, subjects, degree levels). Reports run queries over the collected data,
render as tables and Chart.js graphs, and are stored back into the database as
JSON so a report keeps its historical numbers instead of silently changing when
the underlying data does. A contact log tracks follow-up attempts, which was the
half of the problem the paper process couldn't address at all.

## Where it stands

Submitted, graded, and archived on GitHub in March 2024. MIT licensed.

The live demo is retired, deliberately. It held real personal contact details, and
a hand-rolled authentication system written by one student under deadline is not
something I'm willing to leave running unattended on the public internet. The
source is there to read; the hosted instance isn't coming back.
