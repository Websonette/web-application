# Common web security standard

- Treat request data, headers, cookies, uploaded files, URLs, API responses, and persisted user content as untrusted.
- Authenticate and authorize on the server. Client-side route guards and hidden controls are usability features, not security boundaries.
- Use framework escaping and contextual output encoding. Sanitize only when trusted HTML is an explicit feature.
- Protect state-changing browser requests against CSRF when cookie credentials are used, and configure cookies with appropriate `Secure`, `HttpOnly`, and `SameSite` attributes.
- Restrict CORS to known origins and required methods/headers. Do not combine wildcard origins with credentials.
- Keep secrets and private source outside public document roots. Deny direct access to repository metadata, configuration, dependencies, source maps when inappropriate, and environment files.
- Apply rate limits and abuse controls to authentication, uploads, public mutations, expensive searches, and webhooks according to risk.
- Return stable public error shapes while logging diagnostic detail safely on the server.
