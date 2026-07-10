# CONTRIBUTING_AI.md

> **Living Document**  
> This document can be changed over time as the project evolves.  
> Every AI assistant and developer must always use the latest version of this document before making architectural or implementation decisions.

---

# SIPUS ALIHSAN
## AI Contribution Guidelines

Version : 1.0

Status : Active

Last Updated : July 2026

---

# Purpose

This document defines how AI coding assistants should contribute to the SIPUS ALIHSAN project.

This is NOT a coding tutorial.

This is a project constitution.

Every AI assistant must follow these rules before generating code.

Examples of AI assistants include:

- ChatGPT
- Codex
- Claude Code
- Cursor AI
- Gemini
- GitHub Copilot
- Windsurf
- Continue.dev
- Any future AI coding assistant

---

# Golden Rule

Do not optimize for writing code quickly.

Optimize for maintainability.

The project is expected to live for many years.

Every implementation must prioritize:

- Simplicity
- Readability
- Maintainability
- Scalability
- Consistency

Never sacrifice architecture for short-term convenience.

---

# Project Philosophy

SIPUS ALIHSAN is a School Library Information System.

It is NOT merely a borrowing application.

The architecture must support future expansion without redesign.

Future modules may include:

- QR Code
- Barcode
- RFID
- OPAC
- REST API
- Mobile Application
- Digital Library
- Literacy Analytics
- WhatsApp Notification
- Single Sign-On
- Multi School Support

Always keep future expansion in mind.

---

# Before Writing Code

Every AI assistant MUST:

Read:

README.md

PROJECT_OVERVIEW.md

DECISION_LOG.md

DATABASE_DESIGN.md

CODING_STANDARD.md

Then understand the project architecture first.

Never start coding without understanding the existing design.

---

# Architecture Rules

Never place business logic inside Controller.

Controllers should remain thin.

Business logic belongs to:

Service Layer

Database operations belong to:

Repository Layer

Validation belongs to:

Form Request

Authorization belongs to:

Policy

Shared utilities belong to:

Helpers or Traits.

Never violate this separation.

---

# Database Rules

Database is the single source of truth.

Never hardcode:

Categories

Bookshelves

Publishers

Authors

Book Conditions

Book Sources

Languages

Statuses

Every master data must come from database.

---

# Historical Integrity

Never modify historical transactions.

Historical records must always represent the condition when the transaction occurred.

Example

Borrow Date

Member Name

Member Class

Inventory Code

must remain unchanged even if master data changes later.

Historical integrity is mandatory.

---

# Delete Policy

Avoid physical deletion.

Prefer:

Inactive Status

or

Soft Delete.

Never remove records that are referenced by transactions.

Protect historical consistency.

---

# Import Policy

Student data comes from Excel Import.

Never create direct dependency with external databases.

Future API synchronization is optional.

Current architecture prioritizes independence.

---

# UI Consistency

Every page should follow the same design language.

Required components:

Page Title

Breadcrumb

Summary Cards (when applicable)

Search

Action Buttons

Data Table

Pagination

Confirmation Dialog

Loading Indicator

Success Notification

Error Notification

Do not invent completely different layouts.

---

# CRUD Standard

Every CRUD page should contain:

Create

Edit

View

Delete (Soft Delete when applicable)

Search

Filter

Pagination

Import (if applicable)

Export Excel

Export PDF

Refresh

Maintain consistent placement of buttons.

---

# Naming Convention

Controllers

Singular

BookController

MemberController

Repositories

BookRepository

Services

BookService

Requests

StoreBookRequest

UpdateBookRequest

Models

Book

Member

Inventory

Use meaningful names.

Avoid abbreviations.

---

# Code Style

Follow PSR-12.

Keep methods small.

Keep controllers clean.

Avoid duplicated code.

Write expressive code.

Prefer readability over cleverness.

---

# Laravel Rules

Use:

Eloquent ORM

Form Request

Policies

Route Model Binding

Database Transactions

Resource Collections when appropriate.

Avoid raw SQL unless absolutely necessary.

---

# Migration Rules

Every database change must use Laravel Migration.

Never ask users to manually modify tables.

Never modify production tables directly.

Migration must be reversible whenever possible.

---

# Feature Development

Before implementing a feature:

Understand

Business Process

Database Design

Existing Architecture

Dependencies

Do not implement features in isolation.

Everything must integrate cleanly.

---

# UI Framework

Use only:

Bootstrap 5

Bootstrap Icons

DataTables

Select2

SweetAlert2

Chart.js

Do NOT introduce:

Tailwind

React

Vue

Angular

Livewire

Filament

without project owner approval.

---

# Performance

Avoid unnecessary queries.

Prevent N+1 Query.

Use eager loading.

Index searchable columns.

Paginate large datasets.

Optimize only after maintaining readability.

---

# Security

Always validate input.

Protect against:

CSRF

Mass Assignment

Unauthorized Access

Invalid File Upload

SQL Injection

Never trust user input.

---

# Logging

Important actions should be logged.

Examples:

Login

Logout

Borrow

Return

Import

Export

Delete

Update

System Settings

Audit logging is preferred over silent changes.

---

# Documentation

Whenever introducing:

Module

Database Table

Feature

Architecture Change

Business Rule

Documentation must also be updated.

Documentation is mandatory.

Documentation is part of the project.

---

# Decision Conflicts

If implementation conflicts with:

DECISION_LOG.md

the implementation is wrong.

Architecture decisions have higher priority than generated code.

---

# AI Behaviour Rules

AI should:

Explain architectural reasoning.

Avoid assumptions.

Ask for clarification if business rules are unclear.

Respect existing code.

Avoid unnecessary refactoring.

Do not rewrite working modules simply because another approach exists.

Consistency is more valuable than novelty.

---

# Forbidden Behaviors

Do NOT:

Change database structure without migration.

Replace architecture without approval.

Introduce new frameworks.

Rename existing tables unnecessarily.

Duplicate business logic.

Hardcode master data.

Break historical records.

Ignore documentation.

---

# Preferred Behaviors

Prefer:

Incremental improvements.

Small pull requests.

Readable commits.

Reusable code.

Consistent UI.

Backward compatibility.

---

# When Unsure

If requirements are unclear:

Do not guess.

Document assumptions.

Request clarification.

Protect project integrity.

---

# Final Principle

This project values:

Architecture over shortcuts.

Consistency over creativity.

Documentation over memory.

Maintainability over speed.

Quality over quantity.

Every AI assistant is expected to behave like a Senior Software Engineer working on a long-term enterprise project.

End of Document.