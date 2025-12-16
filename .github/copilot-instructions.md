<!-- Copilot / AI agent instructions for the LocalTrade PHP app -->

# LocalTrade — Copilot Instructions

Purpose: Give AI coding agents the minimal, high-value knowledge to be productive in this repository.

- Runtime: PHP (requires PHP 8.x or newer — this code uses `match` expressions).
- Hosting: Designed to run on a simple LAMP/XAMPP stack. No Node/tooling required; Tailwind is included via CDN.

**Big picture**
- Small server-rendered PHP site (no framework). Pages are individual PHP files in repo root and `Brands/`.
- UI: Tailwind via CDN + inline CSS variables (`--lt-orange`). No compiled CSS pipeline.
- Data: Forms use AJAX to `process/` scripts for validation/DB operations; responses are JSON for client-side error/success display.
- Authentication: Session-based; check `$_SESSION['user']` for login state (array with 'id', 'fullname', 'email', 'type').
- Processing: All form handling in `process/` with mysqli prepared statements; return JSON arrays with 'success' bool and 'errors' array.

**Key files & patterns (examples)**
- Global header include: `header.php` + `header-script.php`. Pages set `$currentPage = 'home'` before `include 'header.php'` for nav styling.
  - Example: `index.php` sets `$currentPage = 'home'; include 'header.php';`
- Signup flow: `signup.php` has buyer/brand toggle; AJAX submits to `process/process-user-signup.php` (handles brand) or future buyer endpoint.
- Login flow: `login.php` AJAX to `process/process-login.php`; sets `$_SESSION['user']` on success.
- Account page: `account.php` shows user info; logout via `process/logout.php`.
- Brand area: files in `Brands/` (e.g. `brand-dashboard.php`) use mock data; replace with DB queries from `Brand` table.
- Product & store routes: pages accept query params, e.g. `product.php?id=1`, `store.php?slug=...`.

**Conventions and project-specific rules**
- No framework routing: add new pages as flat PHP files and link from nav/templates.
- Keep UI classes inline with Tailwind CDN usage — don't introduce a build step unless documented.
- Use PHP 8 features; ensure compatibility when introducing libraries.
- DB: mysqli with prepared statements; config in `config.php` ($conn global).
- Forms: AJAX fetch to `process/` endpoints; handle JSON responses with success/errors display.
- Validation: Server-side in `process/`; client-side for UX but trust server.
- Sessions: Start with `session_start()`; store user array in `$_SESSION['user']`.

**Dev / run / debug notes (concrete)**
- Run locally with XAMPP: place in `htdocs/LocalTrade`, start Apache/PHP, open `http://localhost/LocalTrade/index.php`.
- Debug: Add `ini_set('display_errors', 1); error_reporting(E_ALL);` near top of PHP files.
- DB: MySQL via mysqli; main tables: `Buyer` (id, fullname, email, password, avatar, phone, created_at, status), `Brand` (id, owner_name, brand_name, slug, category, location, email, password, logo, status, verified, created_at), `Product` (id, brand_id, name, slug, category, price, stock, status, main_image, created_at), `Order` (id, order_number, buyer_id, brand_id, status, total, customer_name, shipping_address1, created_at), `Cart` (id, buyer_id, product_id, quantity), `Review` (id, product_id, buyer_id, rating, comment, status), `Wishlist` (id, buyer_id, product_id), `BrandFollower` (id, buyer_id, brand_id), `Address` (id, buyer_id, name, phone, address1, city, state), `Notification` (id, user_type, user_id, type, title, message).

**Integration points & TODOs for agents**
- `process/google_oauth_start.php`: Google OAuth flow (has code for buyer signup).
- `process/process-user-signup.php`: Handles brand signup; buyer signup not yet implemented.
- Payments, shipping, analytics: Not implemented; see TODOs in `Brands/*` for mock data to replace.

**Actionable examples for common tasks**
- Implement buyer signup: Add logic in `process/process-user-signup.php` or new file; insert into `Buyer` table.
- Add auth check: `if (empty($_SESSION['user'])) { header('Location: login'); exit; }`
- DB query: Use `$conn->prepare()`; bind params; execute; handle results.
- AJAX form: `fetch('process/endpoint', {method:'POST', body:fd})`; parse JSON; update DOM with errors/success.

**Safety / constraints for AI edits**
- Do not change global structure without migration note — repo runs in-place on XAMPP.
- Preserve Tailwind CDN; document any build steps added.

If anything above is unclear or you'd like additional examples (e.g. a starter `config.php` and `process/process-user-signup.php` implementation), tell me which area to scaffold next.
