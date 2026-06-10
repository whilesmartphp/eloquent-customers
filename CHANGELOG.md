## [2.0.0] - 2026-06-10
- BREAKING: removed `company_name`; `name` is the canonical display name for both individuals and organizations
- Added a `type` field (`individual` / `organization`), defaulting to `individual`
- Customers can hold people via the contacts package: `HasContacts` exposes `contacts()` and `primaryContact()`

## [1.1.0] - 2026-06-10
- Enforce owner-access authorization on customer endpoints so callers can only read and modify customers in workspaces they belong to
- Make customer search case-insensitive across MySQL and SQLite, not only Postgres

## [1.0.0] - 2026-05-08
- Initial release