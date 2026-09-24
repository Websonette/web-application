# Common web project standard

- Preserve the dependency direction `project -> adapter -> web-application`.
- Keep domain and application behavior in the consuming project. Promote code only after a stable, repeated cross-project use case exists.
- Keep server, client, build, and deployment boundaries explicit. Do not let generated assets or transport details become domain APIs.
- Define public HTTP and serialized contracts deliberately, including compatibility and error behavior.
- Keep configuration external to code, validate it at startup, and never commit secrets.
- Prefer one clear local-development path and one clear production build path. Generated output must be reproducible and excluded from source control unless publication requires it.
- Use stable framework conventions before introducing custom loaders, registries, or lifecycle layers.
- Document ownership whenever the same repository contains backend, frontend, examples, and infrastructure.
