---
title: "Student Tracker (ABM2)"
category: "tools"
summary: "A native Android student portal for managing academic terms, courses, assessments and mentors, with local persistence, date reminders, sharing and responsive phone layouts."
order: 8
caseStudy: true
media:
  type: "image"
  thumbnail: "../../assets/images/portfolio/abm2-student-logo-icon.svg"
  full: "../../assets/images/portfolio/abm2-student-logo.svg"
gallery:
  title: "Student Tracker on Android"
  items:
    - image: "../../assets/images/portfolio/abm2-home-tablet.png"
      alt: "Landscape tablet mockup of the Student Tracker home screen"
      caption: "An early wide-layout mockup exposed how much more a tablet view could do than simply stretch the phone navigation."
    - image: "../../assets/images/portfolio/abm2-home-phone.png"
      alt: "Student Tracker home screen on an Android phone with navigation cards for terms, courses, assessments and mentors"
      caption: "The finished phone home screen pairs the custom Student identity with direct routes into the four primary record types."
    - image: "../../assets/images/portfolio/abm2-navigation-drawer.png"
      alt: "Student Tracker navigation drawer with links to home, terms, courses, assessments and mentors"
      caption: "The same academic structure is available from a persistent drawer throughout the app."
    - image: "../../assets/images/portfolio/abm2-term-detail.png"
      alt: "Winter Term 2023 detail screen showing its dates and assigned course"
      caption: "A term detail view keeps its date range and related courses together, with controls to add, edit or remove records."
    - image: "../../assets/images/portfolio/abm2-course-detail.png"
      alt: "Course detail screen showing status, dates, notes, assessments and mentors"
      caption: "Course details bring the core relationships into one view: status and dates, shareable notes, assessments and mentors."
    - image: "../../assets/images/portfolio/abm2-assessment-editor.png"
      alt: "Edit Assessment screen with type, start and goal dates, reminders and course assignment"
      caption: "Assessment editing supports objective and performance types, course assignment, date selection and independent reminders."
    - image: "../../assets/images/portfolio/abm2-mentor-detail.png"
      alt: "Mentor detail screen showing a mentor's name, email address and phone number"
      caption: "Mentor records keep the contact information associated with a student's coursework."
    - image: "../../assets/images/portfolio/abm2-course-sharing.png"
      alt: "Share Course dialog asking whether to share course details or notes"
      caption: "The Android share sheet can receive either a course's notes or a formatted summary of the full course record."
    - image: "../../assets/images/portfolio/abm2-splash-screen.png"
      alt: "Student Tracker splash screen displaying the custom blue and charcoal S app icon"
      caption: "I designed the fictional Student logo and its icon variant for the app, even though the assignment only required an application icon."
tags: ["Android", "Java", "Room", "SQLite", "Material Design", "View Binding", "Notifications"]
links:
  repo: "https://github.com/crimsonstrife/abm2"
---

Student Tracker is the native Android application I built for Western Governors
University's C196 mobile-development course. The brief was a phone-based student
portal that remained usable in portrait and landscape orientation while letting
a student maintain the structure around a degree program.

The result is a working CRUD application for academic terms, courses,
assessments and mentors. It also schedules date reminders, shares course
information through other Android apps and includes sample data so the
relationships can be explored without entering an entire degree plan first.

## Organizing the degree plan

The data model follows the way the information is used. A term contains courses;
a course carries its status, dates and notes; and assessments and mentors can be
assigned to that course. List screens make each record type easy to scan, while
the term and course detail screens bring their related records together.

Room provides the local database over the application's SQLite store. Entities,
DAOs and a repository separate persistence from the activities, while LiveData,
view models and RecyclerView adapters keep lists and detail screens synchronized.
Course statuses and assessment types are represented as enums because they are
fixed choices rather than user-maintained records.

## Designing for Android

The application uses native Android activities and Material components with a
drawer-based home screen. View Binding connects the Java activities to reusable
XML layouts, including separate landscape resources for wider phone views. The
home screen and navigation drawer both expose the same four-part information
architecture, so moving between record types does not require backing all the
way out of a detail flow.

I also created the fictional **Student** identity used by the application. The
interlocking blue-and-charcoal S became the launcher and splash-screen icon, and
the expanded wordmark gives the otherwise utilitarian home screen a distinct
product identity. Branding beyond an app icon was not part of the requirement;
it was an extra design pass to make the project feel like a coherent app.

## Reminders and sharing

Course start and end dates, along with assessment start and due dates, can
schedule Android notifications through `AlarmManager`, with independent controls
for each milestone.

Course notes can be shared on their own, or the app can format the course title,
description, status, dates and notes into one summary. It hands that text to
Android's standard sharing intent so the student can choose email or any other
compatible application rather than being locked into a single service.

## What the wider layout revealed

The assignment required portrait and landscape phone support, not a dedicated
tablet experience. A tablet mockup made the limitation of simply stretching an
activity layout obvious: the interface remained usable, but left substantial
space that could have shown a list and selected record together.

My retrospective identified a better direction for a future pass: use fragments
for a master-detail tablet layout, reduce the chain of nested layout includes,
finish the contextual help affordances and turn notes into a true multi-note
workflow with selective sharing. It also captured a practical lesson in scope.
Several quality-of-life ideas competed with the required functionality, so the
finished submission ultimately focused on a complete, testable minimum viable
product.

The archive preserves the Android Studio project, final release APK, flow and
layout mockups, build screenshots and the project reflection. The public
repository contains the source for the application itself.
