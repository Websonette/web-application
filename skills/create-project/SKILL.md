---
name: create-project
description: Create a new Websonette web project from common standards and an adapter-owned project profile.
disable-model-invocation: true
---

# Create a Websonette project

You are an orchestrator. Never encode Latte, React, router, CSS framework, state library, or hosting implementation details in this skill.

## Required inputs

Determine the destination, project purpose, and adapter profile. Ask only for choices that materially change the result. Do not assume an adapter when more than one fits.

## Load context before writing

1. Read the organization engineering standards at `https://github.com/Websonette/.github/blob/main/docs/ENGINEERING_STANDARDS.md`.
2. Read every document in this plugin's `docs/standards/` directory and `docs/architecture.md`.
3. Resolve the selected adapter's `scaffold/profile.json` using `docs/PROJECT_PROFILES.md`.
4. Validate that the profile uses schema version 1, extends `Websonette/web-application` contract version 1, and all declared references stay inside the adapter repository.
5. Read every file in the profile's `references` array and its rule when present.
6. Inspect the destination and preserve any existing user work. Stop before overwriting a non-empty destination unless the user explicitly approved integration.

## Create

Translate the user's requirements through the adapter profile. Let the adapter references decide layout, runtime, routing, API integration, state, Docker, deployment, tests, and security. Add only dependencies required by the requested project; optional tools are not defaults.

Create `.websonette/project.json` with the resolved profile id/version, adapter repository, immutable adapter reference, contract version, and `createdBy: "websonette/create-project"`. Copy the adapter rule to the project only when the profile declares one.

## Verify

Install dependencies using the project's chosen package managers. Run formatting where configured, tests, static analysis or type checking, and a production build. Exercise the smallest useful smoke path. Report every command and any check that could not run.

Initialize Git only when requested or when the destination is not already in a repository and the user asked for a repository. Never force-push, rewrite history, or create a remote without explicit authorization.
