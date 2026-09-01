---
title: "Student Tracker (ABM2)"
category: "tools"
summary: "A native Android student portal for managing academic terms, courses, assessments and mentors, with local persistence, reminders and phone/tablet layouts."
order: 8
tags: ["Android", "Java", "Room", "SQLite", "Material Design", "View Binding", "Notifications"]
links:
  repo: "https://github.com/crimsonstrife/abm2"
---

Student Tracker is the mobile application from my Western Governors University
Android coursework. It gives a student one place to maintain the academic
structure around a degree: terms contain courses, courses carry status, dates and
notes, and assessments and mentors can be associated with the course they belong
to.

## Designed for the device

The application uses native Android activities and Material components with a
drawer-based home screen. Portrait and landscape resources adapt the detail and
editing views for phones and wider tablet layouts. Students can create, edit,
search and remove terms, courses, assessments and mentors, share course notes,
and contact a mentor from the stored phone information.

Start, end and goal dates can schedule system notifications through the alarm
manager. The app also includes sample data for exploring the relationships
without building a term from scratch.

## Local data model

Room provides the on-device database over entities for terms, courses,
assessments, mentors and notes. DAOs and a repository layer keep persistence out
of the activities, while adapters and view models prepare the related records for
list and detail screens.

The repository includes a release APK, a finished project write-up and
screenshots would make this entry stronger; until those are recovered, the code
is the evidence for the feature summary here.
