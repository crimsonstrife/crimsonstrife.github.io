---
title: "AutoStore Document Split Scripts"
category: "tools"
summary: "VBScript for a document-capture server that turns one long scanner run into correctly separated files — splitting on barcode prefixes for one workflow and on check numbers for another."
order: 10
tags: ["VBScript", "AutoStore", "Document Capture", "Regex", "Logistics", "GPL-3.0"]
links:
  repo: "https://github.com/crimsonstrife/Cardinal-AutoStoreSplitScripts"
---

Cardinal Logistics was a Modern Impressions customer running AutoStore, a
document-capture server that sits between a scanner and wherever the paper is
supposed to end up. Someone drops a stack in the feeder, and AutoStore is meant
to work out where one document ends and the next begins.

Out of the box it will split on a fixed page count or a blank separator sheet.
Neither helps when the stack is a hundred pages of variable-length paperwork and
the only thing marking the boundaries is the content itself.

## How the split works

AutoStore exposes two script hooks, and the job is spread across both.

The first fires on every value the barcode or OCR reader pulls off a page, and
its only responsibility is to append that value to a running semicolon-delimited
string: `1001;1001;1001;1002;1002;1003`. It doesn't decide anything.

The second fires once the whole batch has been read, walks that string comparing
each value to the one before it, and emits a page grouping in AutoStore's own
notation — `,` to keep the next page in the current document, `;` to start a new
one. Three pages carrying 1001 followed by two carrying 1002 becomes `1,2,3;4,5`.
The value never has to mean anything; it only has to change when the document
changes.

## Two variants of the same idea

The **barcode** version filters what it collects through a regular expression
pinned to the prefixes Cardinal's forms actually used — `^(085|732)` — so stray
reads from elsewhere on the page don't invent a boundary that isn't there.

The **check number** version wants every value on the page, so the filter is
switched off and the accumulator takes whatever comes. Same two-stage structure,
same split notation, different definition of "this page belongs with the last
one". Reading it back now, the disabled branch left a comparison in place that is
always true — harmless, and a fair illustration of what code looks like when it
is written against a machine in a customer's server room at the speed the
customer needs it.

## A note on the software

AutoStore came from Notable Solutions, which Nuance acquired in 2014; that's who
owned it while I was writing these. Kofax bought Nuance's document imaging
business the following year and renamed itself Tungsten Automation in January
2024, so the product is sold under that name today. The scripting hooks are the
same ones.

GPL-3.0. Written in 2018.
