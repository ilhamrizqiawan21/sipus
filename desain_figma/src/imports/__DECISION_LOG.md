# DECISION_LOG.md

# SIPUS ALIHSAN
## Sistem Informasi Perpustakaan MTs Al-Ihsan Batujajar

---

# Document Information

Version : 1.0

Status : Approved

Last Updated : July 2026

Author : Project Architect

Purpose :

This document records every architectural and technical decision taken during the design phase.

Future developers and AI assistants MUST follow these decisions unless there is a very strong technical reason to change them.

Any architecture change must be documented in this file.

---

# Decision 001

## Project Philosophy

Decision

SIPUS ALIHSAN is designed as a professional Library Information System instead of a simple borrowing application.

Reason

The application is expected to grow continuously.

Future features include:

- OPAC
- QR Code
- Barcode
- RFID
- Digital Library
- Reading Analytics
- Literacy Reports
- Mobile Application
- API Integration

Therefore the architecture must be scalable.

Status

APPROVED

---

# Decision 002

## Technology Stack

Decision

Backend

Laravel 12

PHP 8.3+

MySQL 8+

Frontend

Blade

Bootstrap 5

Bootstrap Icons

JavaScript

DataTables

SweetAlert2

Select2

Chart.js

Reason

Simple

Stable

Maintainable

Long-term support

Easy for future developers.

Rejected Technologies

Vue

React

Livewire

Filament

Inertia

Reason

Project prioritizes simplicity and maintainability over SPA experience.

Status

APPROVED

---

# Decision 003

## Desktop First

Decision

The application is optimized for desktop.

Minimum resolution

1366 x 768

Reason

Library staff work using desktop or laptop.

Mobile optimization is secondary.

Status

APPROVED

---

# Decision 004

## Database Independence

Decision

The library owns its own database.

Reason

Avoid dependency on other school systems.

If another application fails, SIPUS continues to operate normally.

Status

APPROVED

---

# Decision 005

## Student Data Import

Decision

Student data is imported using Excel.

No direct synchronization.

No direct database connection.

Reason

Simple maintenance.

No dependency.

Easy migration.

Easy backup.

Works offline.

Status

APPROVED

---

# Decision 006

## Member Table

Decision

Members are stored inside SIPUS.

Reason

Transactions require historical consistency.

Member data may change every academic year.

Borrowing history must never change.

Status

APPROVED

---

# Decision 007

## Transaction Snapshot

Decision

Every borrowing transaction stores:

Member Name

NIS

Class

Borrow Date

Reason

Historical reports must remain accurate.

Future class changes must not affect old transactions.

Status

APPROVED

---

# Decision 008

## Book Architecture

Decision

Separate

Book Master

and

Book Inventory.

Book Master

Stores bibliographic information.

Book Inventory

Stores every physical copy.

Reason

One title can have many copies.

Each copy has its own status.

Example

Master

Fiqih VII

Inventory

INV00001

INV00002

INV00003

Status

APPROVED

---

# Decision 009

## Dynamic Master Data

Decision

All master data comes from database.

Never hardcode.

Examples

Categories

Bookshelves

Publishers

Authors

Book Conditions

Book Sources

Languages

Reason

Schools continuously change.

Future flexibility.

Status

APPROVED

---

# Decision 010

## Soft Delete

Decision

Master data should rarely be physically deleted.

Inactive status is preferred.

Reason

Maintain transaction integrity.

Historical reports remain valid.

Status

APPROVED

---

# Decision 011

## QR Code

Decision

QR Code is postponed.

Version 1 uses manual selection.

Reason

Reduce implementation complexity.

No additional hardware required.

Can be added later without database changes.

Status

APPROVED

---

# Decision 012

## Borrowing Workflow

Decision

Borrowing process

Select Member

↓

Search Book

↓

Select Inventory Copy

↓

Save

Reason

Simple workflow.

Fast.

Minimal training.

Status

APPROVED

---

# Decision 013

## Returning Workflow

Decision

Search Member

↓

Display Borrowed Books

↓

Return Selected Copy

↓

Update Inventory

Reason

Fast operation.

Easy validation.

Status

APPROVED

---

# Decision 014

## UI Philosophy

Decision

Minimalistic.

Professional.

Fast.

Consistent.

Reason

Library software is a productivity tool.

Beauty is secondary.

Efficiency is primary.

Status

APPROVED

---

# Decision 015

## Layout

Decision

Sidebar Left

Top Navbar

Content Right

Reason

Common enterprise interface.

Easy navigation.

Status

APPROVED

---

# Decision 016

## Dashboard

Decision

Dashboard contains

Summary Cards

Recent Activities

Charts

Popular Books

Top Visitors

Late Returns

Reason

Users should understand library status within 10 seconds.

Status

APPROVED

---

# Decision 017

## Table Standardization

Decision

Every data table includes

Search

Pagination

Sorting

Export

Import

Refresh

Reason

Consistent user experience.

Status

APPROVED

---

# Decision 018

## Import Export

Decision

Every master module supports Excel Import and Export.

Reason

Large amount of school data.

Reduce manual input.

Status

APPROVED

---

# Decision 019

## Audit Log

Decision

Every important activity is logged.

Create

Update

Delete

Borrow

Return

Import

Export

Reason

Security.

Traceability.

Accountability.

Status

APPROVED

---

# Decision 020

## Permission System

Decision

Use Spatie Laravel Permission.

Roles

Administrator

Librarian

Head Librarian

Principal (Read Only)

Reason

Industry standard.

Easy maintenance.

Status

APPROVED

---

# Decision 021

## Architecture Pattern

Decision

Business logic is separated.

Controllers

↓

Services

↓

Repositories

↓

Models

Reason

Maintainability.

Testing.

Scalability.

Status

APPROVED

---

# Decision 022

## Coding Standard

Decision

PSR-12

SOLID

Reusable Components

No duplicated code.

Reason

Long-term maintenance.

Status

APPROVED

---

# Decision 023

## Database Design Philosophy

Decision

Normalize database to at least Third Normal Form (3NF).

Reason

Avoid redundancy.

Maintain consistency.

Improve scalability.

Status

APPROVED

---

# Decision 024

## Future Features

Reserved

QR Code

Barcode

RFID

Notification

WhatsApp Gateway

REST API

OPAC

Digital Library

Book Reservation

Self Borrowing

Reason

Architecture prepared from beginning.

Status

PLANNED

---

# Decision 025

## Documentation First

Decision

Documentation is completed before coding begins.

Reason

Reduce redesign.

Reduce technical debt.

Help AI assistants understand project.

Status

APPROVED

---

# Final Principle

Whenever a future decision conflicts with this document,

this document takes precedence.

Any architectural modification must update this file first.

End of Document.