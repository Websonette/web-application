---
name: update-project
description: Update a Websonette project to a newer adapter profile through a reviewed, reversible migration.
disable-model-invocation: true
---

# Update a Websonette project

1. Read `.websonette/project.json`; if it is missing, audit and reconstruct it only from verified installed dependencies and user confirmation.
2. Load organization engineering standards, this plugin's common standards, the currently pinned adapter profile, and the requested target profile.
3. Inspect the repository and current working tree. Preserve unrelated and uncommitted changes.
4. Compare profile references and released migration notes. Produce a migration plan that separates dependency updates, configuration changes, application code changes, data migrations, and removals.
5. Apply the smallest compatible change. Do not replace application-owned layout, design, domain code, or optional libraries merely because the target profile demonstrates a different choice.
6. Update `.websonette/project.json` only after the target checks pass.
7. Run the complete project verification path and describe rollback or forward recovery for changes that affect data or deployment.

Never update from a floating branch unless the user explicitly requests development against it. Never rewrite Git history or force-push.
