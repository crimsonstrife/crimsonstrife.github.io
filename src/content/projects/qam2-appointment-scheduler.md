---
title: "Appointment Scheduler (QAM2)"
category: "tools"
summary: "A JavaFX and MySQL desktop scheduler with localized login, customer and appointment management, time-zone-aware validation, reminders and operational reports."
track: "education"
year: 2022
order: 8
tags: ["Java", "JavaFX", "FXML", "MySQL", "JDBC", "Localization"]
links:
  repo: "https://github.com/crimsonstrife/qam2-task1"
---

QAM2 is a Western Governors University desktop application for managing customer
appointments against a MySQL database. It was built with Java 17, JavaFX, FXML
views and JDBC rather than a web framework.

## What it covers

Authenticated users can create, update and remove customers and appointments,
switch between weekly and monthly schedules, and receive an alert when an
appointment begins within fifteen minutes. Appointment entry checks for overlaps
and validates the organization's Eastern Time business hours while presenting
times in the user's local zone.

The login experience detects English or French from the system locale and logs
successful and failed attempts with timestamps. Reporting views cover
appointments by type and month, schedules by contact, and appointment counts by
user.

## The coursework constraint

The application expects the course-provided `client_schedule` MySQL database and
valid local credentials, so the repository is primarily a source and Javadoc
artifact rather than a self-contained download. A finished project write-up and
screenshots would make this entry stronger; until those are recovered, the code
is the evidence for the feature summary here.
