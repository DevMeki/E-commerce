<!-- Copilot / AI agent instructions for the LocalTrade PHP app -->

# LocalTrade — Copilot Instructions

## Overview
Small server-rendered PHP site (no framework). Runtime: PHP 8.x+; hosted on XAMPP/LAMP. Tailwind via CDN + inline CSS variables. All form handling via AJAX to `process/` endpoints returning JSON.

## Architecture

**Directory structure & purpose**
- **Root** (`.php` files): Public-facing pages. Set `$currentPage = 'home'` before `include 'header.php'` for nav highlighting.
- **`process/`**: AJAX endpoints for form handling (validation, DB ops). Always return JSON with `success` bool and `errors` array. Handle auth checks at endpoint level.
- **`Brands/`**: Separate brand portal with own auth (`Brands/process/check_brand_login.php`). Brand pages check `$_SESSION['user']['type'] === 'brand'`. Has own process directory.
- **`Assets/`**: Product images and avatars (organize by `avatars/`, `products/`).
- **`about/`, `Waitlist/`**: Static HTML sections.

**Session & auth model**
- Check `$_SESSION['user']` after `session_start()`. Structure: `['id' => int, 'fullname' => string, 'email' => string, 'type' => 'buyer'|'brand', ...]`.
- Buyer login: `process/process-login.php` (checks `Buyer` table, sets type='buyer').
- Brand login: `Brands/process/check_brand_login.php` (guards brand pages; redirects to `../login` if not brand).
- Logout: `process/logout.php`.

**DB & queries**
- Global `$conn` (mysqli) from `config.php`. Always use prepared statements: `$stmt = $conn->prepare('SELECT...WHERE id=?'); $stmt->bind_param('i', $id); $stmt->execute();`.
- Main tables: `Buyer`, `Brand`, `Product`, `Order`, `Cart`, `Review`, `Wishlist`, `BrandFollower`, `Address`, `Notification`. See `localtrade_db.sql` for schema.
- Views: `activeproducts` (product + brand details joined).

**Form handling pattern**
1. Form POSTs to `process/endpoint.php` via fetch.
2. Endpoint validates, prepares response: `['success' => false, 'errors' => [...]]`.
3. Always `header('Content-Type: application/json')` before echo.
4. Client parses response, shows errors or redirects on success.
Example endpoint structure (from `process-login.php`):
```php
header('Content-Type: application/json');
$response = ['success' => false, 'errors' => []];
// ... validation ...
if ($errors) {
    echo json_encode($response);
    exit;
}
// ... DB insert/update ...
$response['success'] = true;
echo json_encode($response);
```

## Patterns & Conventions

**UI / Tailwind**
- No build step. CDN with theme override in each page's `<script>`: `tailwind.config = { theme: { extend: { colors: {...} } } }`.
- CSS variables for brand colors: `--lt-orange`, `--lt-forest`, `--lt-parchment`, `--lt-ink`, `--lt-cream`.

**Adding pages**
- Create `.php` file in root or `Brands/`.
- Set `$currentPage = 'pagename'` before `include 'header.php'` (header.php applies active nav styling).
- Include DB config: `if (file_exists('config.php')) require_once 'config.php';` (adjust path if nested).

**Auth checks**
- Public pages: no session check needed.
- Buyer-only pages: `if (empty($_SESSION['user'])) { header('Location: login'); exit; }`.
- Brand-only pages: Include `Brands/process/check_brand_login.php` (auto-redirects if not brand).

**Process endpoints**
- Check `$_SERVER['REQUEST_METHOD'] === 'POST'` first.
- Always return JSON. Errors don't require HTTP 400/500; just `success: false, errors: [...]`.
- Include config path check: `if (file_exists('../../config.php')) require_once '../../config.php';` (account for nesting).

**Cross-page communication**
- Brands area is independent. Brand user data stored in `Brand` table. Brand pages use `$_SESSION['user']['id']` to reference `Brand.id`.
- Buyers in `Buyer` table. Orders link both via `Order.buyer_id` and `Order.brand_id`.

## Dev / Run / Debug

- **Local**: XAMPP, place in `htdocs/LocalTrade`, open `http://localhost/LocalTrade/index.php`.
- **Debug**: Add `ini_set('display_errors', 1); error_reporting(E_ALL);` to top of file.
- **DB**: Import `localtrade_db.sql` into MySQL. Default config: `root` (no pwd), `localtrade_db` db.

## Integration Points & TODOs

- **`process/process-user-signup.php`**: Buyer signup logic. Inserts into `Buyer` table.
- **`process/google_oauth_start.php`**: Google OAuth (partially implemented).
- **`Brands/onboarding.php`**: Brand onboarding (line 55 TODO: save to DB).
- **Payments, shipping, analytics**: Not implemented. Search `Brands/*` for mock data placeholders.

## Safety Constraints

- Don't change global structure without MIGRATION note. Runs in-place on XAMPP.
- Preserve Tailwind CDN. No build steps unless documented.
- Use PHP 8.0+ features (match expressions, named args, etc.).

If anything above is unclear or you'd like additional examples (e.g. a starter `config.php` and `process/process-user-signup.php` implementation), tell me which area to scaffold next.
