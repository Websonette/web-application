# Common hosting standard

- Prefer a dedicated public document root containing only entrypoints and built public assets.
- When shared hosting forces the repository root to be public, explicitly deny direct requests to backend source, frontend source, dependencies, environment files, VCS data, build configuration, and manifests. Test those denials.
- Route API requests to the server entrypoint before applying SPA history fallback.
- Serve static files only when the resolved path is an existing intended public file; do not permit traversal or internal rewrite loops.
- Support subpath deployments by making frontend base paths and server rewrites explicit.
- Keep HTTPS termination, proxy headers, compression, and cache behavior documented for the actual hosting environment.
