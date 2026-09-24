# Project profiles

An application adapter publishes `scaffold/profile.json`. The profile identifies the adapter, declares its dependency on `websonette/web-application`, and lists the adapter-owned references that an orchestration skill must read before creating, updating, or auditing a project.

The profile format is defined by [`scaffold/profile.schema.json`](../scaffold/profile.schema.json). Profiles contain decisions and references, not a second package manager. Actual Composer and npm manifests remain authoritative for released dependencies.

Generated projects record the resolved profile in `.websonette/project.json`:

```json
{
  "$schema": "https://raw.githubusercontent.com/Websonette/web-application/main/scaffold/project.schema.json",
  "schemaVersion": 1,
  "profile": "react-spa",
  "profileVersion": "0.1.0",
  "adapterRepository": "Websonette/react-spa-application",
  "adapterReference": "0.1.0",
  "contractVersion": 1,
  "createdBy": "websonette/create-project"
}
```

Pin `adapterReference` to a release tag or immutable commit for reproducible creation. `main` is acceptable only for deliberate development work.

## Resolution order

Skills resolve a profile without assuming an adapter implementation:

1. an explicit local adapter path supplied by the user;
2. the installed Composer or npm adapter package;
3. the repository and immutable reference recorded in `.websonette/project.json`;
4. for a new project, an adapter repository selected by the user.

Every path in `references` is relative to the adapter repository root. The orchestrator must load all required references and reject unknown schema versions or missing files before writing project code.
