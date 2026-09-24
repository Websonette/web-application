# Common Docker standard

- Docker is an optional delivery mechanism, not a runtime requirement of `web-application`.
- Use small, pinned base images and multi-stage builds when they materially reduce the production image.
- Run application processes as a non-root user where practical.
- Keep development bind mounts and tooling out of production images.
- Do not bake credentials or environment-specific values into images or build layers.
- Add health checks only for meaningful readiness/liveness signals; do not report healthy before required dependencies are usable.
- Keep service names, ports, and volumes configurable so multiple projects can run concurrently.
- Build the same immutable artifact that will be deployed and verify it in CI.
