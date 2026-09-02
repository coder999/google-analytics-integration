# ga4 — Claude instructions

## Nothing in `src/` may touch storage, globals, or the environment

No PDO, no database connection, no settings table, no `$_SESSION`,
no `$_GET`, no `getenv()`. Configuration arrives through constructor
arguments; caching goes through `CacheInterface`; HTTP goes through
`HttpInterface`. This is what lets one package serve a DB-backed site,
a site with no database at all, and a CLI, instead of the four
divergent copies of `ga.php` this package replaced. See
`vps-infra/docs/superpowers/specs/2026-09-01-ga4-package-design.md`.

## No runtime dependencies

`require` is PHP plus three extensions. Adding a Composer runtime
dependency means every consuming site's committed `vendor/` grows by
it — see the spec's rationale for rejecting `google/analytics-data`.

## Tests run in Docker

nexus has no PHP or Composer on the host. Use `bin/dev test`.
Tests must not touch the network; `Client`, `Dashboard` and `Admin`
are all tested against `FakeHttp`.
