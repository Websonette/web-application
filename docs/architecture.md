# Web application architecture

`websonette/web-application` is the framework-independent foundation for Websonette web applications. It owns request-scoped PHP state, serialized contracts, common web standards, and project workflow orchestration. It does not own rendering, routing, browser frameworks, application layout, or business behavior.

The dependency direction is fixed:

```text
consuming project -> application adapter -> web-application
```

Current adapters are:

- `websonette/latte-application` for Nette, Latte, Naja, and its Vite conventions;
- `websonette/react-spa-application` and `@websonette/react-spa-application` for a Nette/Contributte Apitte backend and React frontend driven by the shared contracts.

Adapters may interpret the contracts and add framework integration. They must not move adapter-specific behavior into this repository. A consuming project owns concrete routes, layouts, design systems, authentication UI, domain components, deployment values, and infrastructure credentials.

## Runtime and tooling are separate

`src/` remains a small Composer runtime with no Nette, Latte, React, browser, or response-format dependency. `contracts/` describes the serialized boundary. `docs/standards/`, `skills/`, `rules/`, and `scaffold/` are development tooling and are not runtime dependencies.

The `create-project` skill is an orchestrator. It loads organization standards, these common web standards, and a selected adapter profile. It does not contain Latte, React, Naja, Vite, router, or project-layout decisions itself.

## Contract versioning

The schemas in `contracts/` are canonical for cross-runtime serialization. Additive compatible fields may be introduced within a contract version when consumers tolerate unknown fields. Removing fields, changing their meaning or type, or making optional data mandatory requires a new contract version and a documented adapter migration.
