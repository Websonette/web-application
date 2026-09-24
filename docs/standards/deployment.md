# Common deployment standard

- Deploy immutable, versioned artifacts produced by CI; do not build from mutable source on the production host unless the hosting platform requires it and the trade-off is documented.
- Run tests, static analysis/type checking, and production builds before publishing an artifact.
- Separate deployment from database or data migrations when failure and rollback characteristics differ.
- Validate required configuration before accepting traffic.
- Use atomic or staged releases where available and keep a documented rollback or forward-recovery procedure.
- Cache static fingerprinted assets aggressively. Do not apply immutable caching to HTML, API responses, or unfingerprinted files without an explicit policy.
- Record the source revision and application version in diagnostics without exposing sensitive details.
