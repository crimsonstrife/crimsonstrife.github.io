---
title: "Inventory Manager (QKM2)"
category: "tools"
summary: "A JavaFX inventory application for maintaining in-house and outsourced parts, assembling products from those parts, and enforcing stock constraints."
track: "education"
year: 2022
yearApprox: true
order: 8
tags: ["Java", "JavaFX", "FXML", "Gradle", "Object-Oriented Design"]
links:
  repo: "https://github.com/crimsonstrife/qkm2-task1"
---

QKM2 is a JavaFX desktop application built for a Western Governors University
software course. It models an inventory as parts and products: parts can be made
in-house or outsourced, and products hold their own collection of associated
parts.

## The application flow

The main screen provides searchable tables for both record types. Separate FXML
forms handle adding and modifying parts and products, while confirmation and
validation messages protect the destructive paths. A product cannot be removed
while it still has associated parts, and stock values are checked against the
minimum and maximum bounds entered for each record.

The implementation separates the model classes, shared inventory collection and
JavaFX controllers, with Gradle carrying the desktop build. A finished project write-up and
screenshots would make this entry stronger; until those are recovered, the code
is the evidence for the feature summary here.
