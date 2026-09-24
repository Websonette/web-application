---
name: audit-project
description: Audit a Websonette project against engineering standards, common web standards, and its pinned adapter profile.
---

# Audit a Websonette project

Audit before changing code. Read-only inspection is the default.

1. Load organization engineering standards and every common standard in this plugin.
2. Read `.websonette/project.json`, resolve the exact adapter profile, validate it, and load all declared references.
3. Inspect dependency direction, public/runtime boundaries, configuration and secrets handling, contract compatibility, tests, build, Docker/hosting, deployment, and security.
4. Run non-destructive checks when dependencies are already available. Do not install, upgrade, or rewrite files unless the user asks for remediation.
5. Report findings ordered by impact and confidence, with file references and concrete evidence. Distinguish violations from optional improvements and avoid proposing abstraction without a demonstrated need.
