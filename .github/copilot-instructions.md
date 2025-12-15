<!-- Copilot / AI agent instructions for the LocalTrade PHP app -->

# LocalTrade — Copilot Instructions

Purpose: Give AI coding agents the minimal, high-value knowledge to be productive in this repository.

- Runtime: PHP (requires PHP 8.x or newer — this code uses `match` expressions).
- Hosting: Designed to run on a simple LAMP/XAMPP stack. No Node/tooling required; Tailwind is included via CDN.

**Big picture**
- Small server-rendered PHP site (no framework). Pages are individual PHP files in repo root and `Brands/`.
- UI: Tailwind via CDN + inline CSS variables (`--lt-orange`). No compiled CSS pipeline.
- Data: Most pages use mock arrays / placeholder logic directly in templates (e.g. `Brands/brand-dashboard.php`, `index.php`).
- Processing: Form/action stubs live in `process/` (many files are placeholders). Expect to move form handling and DB work into `process/` scripts or a central `config.php`/`db.php` include.

**Key files & patterns (examples)**
- Global header include: `header.php` + `header-script.php`. Pages set `$currentPage = 'home'` (or similar) before `include 'header.php'` to get active nav styling.
  - Example: `index.php` sets `$currentPage = 'home'; include 'header.php';`
- Signup flow: `signup.php` currently renders the form and validates on the same page (POST to self). There are empty stubs under `process/` (`process-user-signup.php`, `google_oauth_start.php`) indicating intended separation.
- Brand area: files live in `Brands/` (e.g. `brand-dashboard.php`, `add-product.php`, `products.php`). These are server-rendered templates with mock data — replace mocks with DB queries.
- Product & store routes: pages accept query params, e.g. `product.php?id=1` and `store.php` (store slug pattern exists visually in forms: `localtrade.ng/store/<slug>`).

**Conventions and project-specific rules**
- No framework routing: add new pages as flat PHP files and link them from nav/templates.
- Keep UI classes inline with Tailwind CDN usage — don't introduce a build step unless you add a new workflow and document it.
- Use PHP 8 features; ensure compatibility when introducing libraries.
- There is no central config yet. Create `config.php` (PDO connection, common helpers) and include it near top of `process/` handlers and pages that need DB access.

**Dev / run / debug notes (concrete)**
- Run locally with XAMPP or similar. Place project in `htdocs/LocalTrade` and start Apache/PHP. Then open: `http://localhost/LocalTrade/index.php`.
- To quickly enable verbose errors for debugging, add near the top of a page (temporary):
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- For Windows PowerShell + XAMPP: ensure Apache is started using the XAMPP Control Panel. No npm/yarn required.

**Integration points & TODOs for agents**
- `process/google_oauth_start.php` — placeholder for Google OAuth start flow.
- `process/process-user-signup.php` — empty; intended for server-side signup logic (DB insert, hashing, redirects).
- Payments, shipping, analytics: not implemented; look for TODOs and `mock data` comments in `Brands/*` and implement integrations in `process/` and `includes/`.

**Actionable examples for common tasks**
- Implement signup: create `config.php` with PDO; move validation/DB insert into `process/process-user-signup.php`; redirect back to `signup.php` with success/errors.
- Pass active nav: set `$currentPage = '<name>'` before `include 'header.php'` to apply active styling (see `index.php` and `header.php`).
- Add brand dashboard data: replace mock arrays in `Brands/brand-dashboard.php` with queries to your DB and keep helper `moneyNaira()`.

**Safety / constraints for AI edits**
- Do not change global structure without adding a short migration note (e.g., adding `config.php`) — the repo is intended to run in-place on XAMPP.
- Preserve Tailwind usage via CDN unless you add a documented build step.

If anything above is unclear or you'd like additional examples (e.g. a starter `config.php` and `process/process-user-signup.php` implementation), tell me which area to scaffold next.
