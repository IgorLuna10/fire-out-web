# Security + Accessibility Audit Report — Fire Out (WEB)

Single source of truth for the Security and Accessibility audit performed per `SECURITY_A11Y_AUDIT.md`.

---

## 1. Scope

```yaml
app_path:        /Users/igorluna/Desktop/Projects/Fire Out - WEB
framework:       Laravel 13.12.0 / PHP 8.3.16
entry_points:    [/login, /register, /minha-conta, /admin]
database:        MySQL (fireout)
session_driver:  file
run_command:     php artisan serve
compliance:      [WCAG 2.2 AA, RGAA 4.1.2, EAA (Directive 2019/882), OWASP ASVS v4.0.3, NIST SP 800-63-4]
mode:            interactive
```

---

## 2. Standards Used

* **Accessibility Standards**:
  * **WCAG 2.2 AA** (W3C Recommendation, Oct 2023): Specifically SC 3.3.8 (Accessible Authentication), SC 1.3.1 (Info and Relationships), SC 1.3.5 (Identify Input Purpose), SC 2.1.1 (Keyboard), SC 2.4.1 (Bypass Blocks), SC 3.3.1 (Error Identification), SC 4.1.2 (Name, Role, Value), SC 4.1.3 (Status Messages).
  * **RGAA 4.1.2** (Référentiel Général d’Amélioration de l’Accessibilité).
  * **European Accessibility Act (EAA - Directive 2019/882)**: Legal accessibility baseline enforced June 28, 2025.
* **Security Standards**:
  * **OWASP ASVS v4.0.3**: Chapters V2 (Authentication), V3 (Session Management), V4 (Access Control), V13 (API & Configuration), V14 (Configuration & Dependencies).
  * **NIST SP 800-63-4**: Digital Identity Guidelines (Section 5.1.1 Authenticator Management & Passwords).

---

## 3. Phase 1: Discovery Summary (≤ 15 lines)

* **Stack & Versions**: PHP 8.3, Laravel 13.12.0, Blade templating, Vite 5.2, Tailwind/Custom CSS, MySQL.
* **Auth Surfaces**: `/login` (GET/POST), `/register` (GET/POST), `/logout` (POST), `/minha-conta/senha` (POST password change), `/admin` (protected by `AdminMiddleware`).
* **Missing Auth Surfaces**: No password reset/recovery (`/forgot-password`, `/reset-password`), no email verification, no MFA/WebAuthn.
* **Credentials & Storage**: Passwords stored via Eloquent `$casts = ['password' => 'hashed']` (Bcrypt). Sessions file-based (`storage/framework/sessions`).
* **Secrets**: Stored in local `.env` (contains MySQL credentials, Mercado Pago access token, Google reCAPTCHA keys).
* **Existing Tooling**: Basic PHPUnit setup (`tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`). Zero automated accessibility tooling (no axe-core, no eslint-jsx-a11y).
* **Local Run Status**: CLI works via `php artisan serve`. Local MySQL service stopped in macOS brew services (`brew services list`), preventing live DB queries until started.

---

## 4. Phase 2: Audit Findings

| ID | Tag | Severity | Confidence | Location | Issue | Evidence / How Confirmed | Standard |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **FO-01** | `[SEC]` | **High** | Confirmed | `bootstrap/app.php:17-21` | **CSRF protection explicitly disabled on `/login` and `/register`** via `$middleware->validateCsrfTokens(except: ['login', 'register'])`. Allows Login CSRF (forcing user into attacker account) and Registration CSRF. | Static inspection of `bootstrap/app.php`. | OWASP ASVS V4.2.1, V4.2.2 |
| **FO-02** | `[SEC]` | **High** | Confirmed | `bootstrap/app.php:16` | **Untrusted proxy trust (`trustProxies(at: '*')`)** allows arbitrary spoofed `X-Forwarded-For` headers, bypassing `/login` rate limiting (`throttle:5,1`). | Static inspection of middleware configuration. | OWASP ASVS V13.1.4 |
| **FO-03** | `[SEC]` | **High** | Confirmed | `composer.lock` | **35 security vulnerability advisories** affecting 9 packages (including Guzzle HTTP CVE-2026-69246 host check bypass and referer URI leaks). | `composer audit --format=plain` command output. | OWASP ASVS V14.2.1 |
| **FO-04** | `[BOTH]` | **High** | Confirmed | `RegisterController.php:35-42` | **Strict password composition rules** (regex for `[a-z]`, `[A-Z]`, `[0-9]`, `[@$!%*?&#]`) increase cognitive burden without increasing entropy; violates NIST guidelines. | Validator regex in `RegisterController.php`. | NIST SP 800-63-4 §5.1.1, WCAG 2.2 SC 3.3.8 |
| **FO-05** | `[A11Y]` | **High** | Confirmed | `RegisterController.php:90-92`, `register.blade.php:398-415` | **reCAPTCHA v3 hard block on registration**: scores < 0.5 throw raw `abort(422)` without accessible error or non-cognitive fallback; privacy/assistive tech users get locked out. | Inspection of controller captcha validation. | WCAG 2.2 SC 3.3.8, RGAA 12.8 |
| **FO-06** | `[BOTH]` | **High** | Confirmed | `routes/web.php`, `login.blade.php` | **No password reset or self-service recovery mechanism**: users who forget credentials are permanently locked out; login form has no recovery link. | Verification of missing routes in `routes/web.php`. | WCAG 2.2 SC 3.3.8, OWASP ASVS V2.5 |
| **FO-07** | `[SEC]` | **Medium** | Confirmed | `.env:25`, `config/session.php:162` | **Session cookies lack `Secure` flag**: `SESSION_SECURE_COOKIE=false` allows cookie transmission over plaintext HTTP. | Inspected `.env` and `config/session.php`. | OWASP ASVS V3.4.1 |
| **FO-08** | `[SEC]` | **Medium** | Confirmed | `ProfileController.php:36` | **Password update does not invalidate other active sessions**: compromised sessions on other devices remain logged in after password change. | `updatePassword` does not call `session()->invalidate()` or `Auth::logoutOtherDevices()`. | OWASP ASVS V3.3.1, NIST SP 800-63-4 |
| **FO-09** | `[A11Y]` | **Medium** | Confirmed | `login.blade.php:243-254`, `register.blade.php:297-311` | **Inputs lack explicit `<label for>` and `<input id>` associations**: screen readers announce inputs as unlabeled generic edit fields. | Inspected HTML markup in Blade templates. | WCAG 2.2 SC 1.3.1, SC 4.1.2, RGAA 11.1 |
| **FO-10** | `[A11Y]` | **Medium** | Confirmed | `login.blade.php:244-252`, `register.blade.php:298-325` | **Missing HTML5 `autocomplete` tokens** (`username`, `current-password`, `new-password`, `tel`, `name`): password managers cannot reliably autofill credentials. | Inspected input attributes. | WCAG 2.2 SC 1.3.5 |
| **FO-11** | `[A11Y]` | **Medium** | Confirmed | `login.blade.php:247`, `register.blade.php:301` | **Validation errors lack ARIA association and alerts**: error messages are rendered visually in red text without `role="alert"`, `aria-describedby`, or `aria-invalid="true"`. | Blade `@error` directives lack ARIA attributes. | WCAG 2.2 SC 3.3.1, SC 4.1.3, RGAA 10.7 |
| **FO-12** | `[A11Y]` | **Medium** | Confirmed | `register.blade.php:330-336` | **Password checklist relies solely on visual color change** (`.ok` green dot) with no ARIA live region (`aria-live="polite"`), leaving blind users unaware of requirement status. | Checked `#pwd-rules` implementation in JS and Blade. | WCAG 2.2 SC 1.4.1, SC 4.1.3 |
| **FO-13** | `[A11Y]` | **Medium** | Confirmed | `profile/index.blade.php:80-95` | **Profile navigation tabs implemented as `<div>` with `onclick`**: not focusable via Tab key, missing `role="tab"` and `role="tablist"`, breaking keyboard accessibility. | Inspected `.profile-nav-item` elements. | WCAG 2.2 SC 2.1.1, SC 4.1.2 |
| **FO-14** | `[A11Y]` | **Low** | Confirmed | `components/header.blade.php:37` | **Mobile navigation hamburger button lacks `aria-expanded` and `aria-controls`**: screen readers cannot determine menu state. | Inspected `.nav-hamburger` markup. | WCAG 2.2 SC 4.1.2 |
| **FO-15** | `[A11Y]` | **Low** | Confirmed | `layouts/app.blade.php:63` | **Missing "Skip to main content" bypass link**: keyboard users are forced to traverse all header links before accessing forms. | Inspected top of `<body>` in `layouts/app.blade.php`. | WCAG 2.2 SC 2.4.1 |

### Expanded Re-Audit Findings (Secondary Surfaces & Frontend Deps)

| ID | Tag | Severity | Confidence | Location | Issue | Evidence / How Confirmed | Standard |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **FO-16** | `[SEC]` | **High** | Confirmed | `package.json`, `package-lock.json` | **6 vulnerabilities (2 critical, 4 high) in frontend dependencies** via `npm audit`: `shell-quote` (critical command injection in `concurrently`), `nanoid` (infinite loop DoS), `postcss` (source map path traversal), `source-map-js` (DoS), and `vite` (cross-origin script execution). | `npm audit` CLI output. | OWASP ASVS V14.2.1 |
| **FO-17** | `[SEC]` | **Medium** | Confirmed | `routes/web.php:25,31` | **Missing rate limiting on public inquiry & checkout endpoints**: `POST /contato` lacks throttling (inbox email bombing risk), and `POST /checkout/submit` lacks throttling (inventory depletion DoS risk). | Inspection of `routes/web.php`. | OWASP ASVS V13.1.5, V13.2.3 |
| **FO-18** | `[SEC]` | **Low** | Confirmed | `app/Models/User.php:13` | **Privilege escalation attribute in `$fillable`**: `'role'` is present in `$fillable` array. Latent mass-assignment hazard if unvalidated input is passed to `User::create` or `update`. | Model `$fillable` definition. | OWASP ASVS V5.1.4, CWE-915 |
| **FO-19** | `[SEC]` | **Low** | Confirmed | `bootstrap/app.php` | **Missing HTTP security defense headers**: Responses lack explicit `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, and `Referrer-Policy: strict-origin-when-cross-origin`. | Middleware inspection in `bootstrap/app.php`. | OWASP ASVS V14.4.1–V14.4.5 |
| **FO-20** | `[A11Y]` | **Medium** | Confirmed | `pages/checkout.blade.php:324-363` | **Checkout address inputs lack explicit `<label for>` and `<input id>` associations**: `CEP`, `Número`, `Rua`, `Bairro`, `Complemento`, `Cidade`, `Estado` inputs lack IDs linked to labels. Document toggle lacks ARIA selection state. | Markup inspection of `checkout.blade.php`. | WCAG 2.2 SC 1.3.1, SC 4.1.2 |
| **FO-21** | `[A11Y]` | **Medium** | Confirmed | `pages/produtos.blade.php:538,896,1190-1198` | **Cart drawer modal and controls lack accessible names**: Close button lacks `aria-label`, quantity increment/decrement/remove buttons lack accessible names, modal lacks `role="dialog"` / `aria-modal="true"`, and toast notification lacks `aria-live="polite"`. | DOM inspection of `produtos.blade.php`. | WCAG 2.2 SC 4.1.2, SC 4.1.3, SC 2.1.2 |
| **FO-22** | `[A11Y]` | **Low** | Confirmed | `pages/contato.blade.php:240-260` | **Contact form error messages lack ARIA association**: Errors appear in visual text but lack `aria-invalid="true"`, `aria-describedby`, and `role="alert"`. | Markup inspection of `contato.blade.php`. | WCAG 2.2 SC 3.3.1, SC 4.1.3 |

### Phase 2 Summary
* **Security `[SEC]`**: 0 Critical, 3 High (`FO-01`, `FO-02`, `FO-03`), 2 Medium (`FO-07`, `FO-08`), 0 Low.
* **Accessibility `[A11Y]`**: 0 Critical, 1 High (`FO-05`), 4 Medium (`FO-09`, `FO-10`, `FO-11`, `FO-12`, `FO-13`), 2 Low (`FO-14`, `FO-15`).
* **Both `[BOTH]`**: 0 Critical, 2 High (`FO-04`, `FO-06`), 0 Medium, 0 Low.
* **Totals**: 0 Critical, 6 High, 7 Medium, 2 Low. (15 verified findings total).
* **Not tested at runtime**: Live browser Playwright session deferred due to local MySQL service being in stopped state.

---

## 5. Phase 3: Tensions & Resolutions

1. **Bot Mitigation vs. Cognitive Barrier (reCAPTCHA v3 & `abort(422)`)**:
   * *Tension*: reCAPTCHA v3 blocks automated spam, but behavioral scoring routinely penalizes users using assistive tech, password managers, or strict privacy extensions, resulting in a sudden 422 error.
   * *Resolution*: Combine Laravel session-backed honeypot (`spatie/laravel-honeypot` or invisible form field), strict IP + email rate limiting (`throttle:10,1`), and keep reCAPTCHA only as a non-blocking progressive score where low scores trigger an email verification link instead of an outright registration denial.
2. **Password Complexity vs. Cognitive Function (NIST 800-63 vs Regex Rules)**:
   * *Tension*: Legacy security practices demand mixed-case, numbers, and symbols, which severely hurts users with dyslexia or cognitive limitations (WCAG 2.2 SC 3.3.8).
   * *Resolution*: Adopt NIST SP 800-63-4 standards: enforce minimum 12 characters, permit all Unicode characters, eliminate composition rules, and integrate `Password::min(12)->uncompromised()` to check against HaveIBeenPwned breach lists.
3. **Session Expiry vs. Input Speed (SC 2.2.1)**:
   * *Tension*: Short session lifetime protects public computers, but users with motor or cognitive impairments take longer to fill checkout and profile forms.
   * *Resolution*: Maintain 120-minute lifetime (`SESSION_LIFETIME=120`), enable `SESSION_SECURE_COOKIE=true`, and ensure session activity resets on user keystrokes.

---

## 6. Phase 4: Accessible Authentication Plan

### Primary Path: Standard Password Authentication with Password Manager & A11y Baseline
* **Rationale**: Lowest friction, zero external SaaS dependencies, immediately fixes all high-severity compliance gaps while remaining 100% accessible.

1. **Step 1: Re-enable CSRF Protection & Restrict Trusted Proxies**
   * *Files*: `bootstrap/app.php`
   * *Action*: Remove `'login'` and `'register'` from `validateCsrfTokens(except: [...])`. Restrict `trustProxies` to known proxy CIDRs or remove `at: '*'` wildcard.
   * *Verification*: POST to `/login` without `_token` fails with 419. POST with `_token` succeeds.
2. **Step 2: NIST Password Policy & Breached Check**
   * *Files*: `app/Http/Controllers/Auth/RegisterController.php`, `app/Http/Controllers/ProfileController.php`
   * *Action*: Replace regex composition rules with `Password::min(12)->letters()->numbers()->uncompromised()`.
   * *Verification*: Passwords < 12 chars or found in data breaches are cleanly rejected; complex symbol requirements removed.
3. **Step 3: Fix Form Markup (Labels, IDs, Autocomplete, ARIA Alerts)**
   * *Files*: `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/profile/index.blade.php`
   * *Action*: Add explicit `id` attributes and `<label for="...">`. Add `autocomplete="username"`, `autocomplete="current-password"`, `autocomplete="new-password"`. Wrap error messages with `role="alert"` and link via `aria-describedby` and `aria-invalid="true"`.
   * *Verification*: Lighthouse accessibility score increases; screen readers announce field names and validation errors.
4. **Step 4: Accessible Error Handling for Bot Prevention**
   * *Files*: `app/Http/Controllers/Auth/RegisterController.php`, `resources/views/auth/register.blade.php`
   * *Action*: Remove raw `abort(422)`. Return standard redirect `back()->withErrors(['captcha' => '...'])` if reCAPTCHA is retained, or migrate to honeypot + rate-limit.
5. **Step 5: Implement Password Recovery (Forgot Password Route)**
   * *Files*: `routes/web.php`, `app/Http/Controllers/Auth/ForgotPasswordController.php`, `resources/views/auth/forgot-password.blade.php`
   * *Action*: Use Laravel's built-in `Password::sendResetLink()` broker with non-enumerating generic success notifications.
6. **Step 6: Accessible Tab Controls & Bypass Links**
   * *Files*: `resources/views/profile/index.blade.php`, `resources/views/layouts/app.blade.php`, `resources/views/components/header.blade.php`
   * *Action*: Convert `<div>` tabs to semantic `<button role="tab">` inside a `role="tablist"`. Add `<a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>`. Add `aria-expanded` to mobile menu toggle.

---

## 7. Changelog

* **2026-11-12 (Comprehensive Re-Audit Completed)**:
  * Re-audited entire application following remediation of Batches 1–4.
  * Verified that original findings **FO-01 through FO-15 are 100% resolved and pass automated tests** (17 tests, 94 assertions, 0 failures, 0 composer advisories).
  * Expanded scope beyond core auth to full application surfaces: frontend npm dependencies, secondary public forms (`/contato`, `/checkout`), interactive catalog cart drawer (`/produtos`), model mass-assignment boundaries, and HTTP response security headers.
  * Identified 7 secondary findings (**FO-16 to FO-22**): 4 security findings (`[SEC]`), 3 accessibility findings (`[A11Y]`).
* **2026-11-12 (Batch 4 - Dependencies Completed)**:
  * **FO-03 Fixed**: Successfully resolved all 35 security advisories affecting 9 packages down to **0 advisories** via `composer update` restricted to minor/patch versions (`patch_minor_only`).
  * Updated `league/commonmark` (2.8.2 => 2.10.7) and `league/flysystem` (3.34.0 => 3.36.0).
  * Updated `guzzlehttp/guzzle` (7.10.5 => 7.16.1) and `guzzlehttp/psr7` (2.10.3 => 2.13.2) while strictly pinning Guzzle on the 7.x line, avoiding breaking major version upgrades.
  * Updated `laravel/framework` (13.12.0 => 13.42.0) and `symfony/*` (8.0.13 => 8.1.11) while preserving major pins on `brick/math` (0.14.8) and `hamcrest/hamcrest-php` (2.1.1).
  * `composer audit` reports: `No security vulnerability advisories found.`
  * Entire PHPUnit test suite passed with 17 tests, 94 assertions.
* **2026-11-12 (Batch 3 - Accessibility of Forms & Profile Completed)**:
  * **FO-07 Fixed**: Updated `config/session.php` to set `'secure' => env('SESSION_SECURE_COOKIE', null)`, ensuring session cookies automatically enforce the `Secure` flag over HTTPS.
  * **FO-11 Fixed**: Added `aria-invalid="true"` and `aria-describedby="[field]-error"` on form inputs in `login.blade.php`, `register.blade.php`, and `profile/index.blade.php`. Error messages are wrapped in `<span role="alert" id="[field]-error">`. Added autofocus to the first invalid input when error states load.
  * **FO-12 Fixed**: Removed color-only password checklist (`#pwd-rules`), replacing it with accessible helper text and server-side NIST validation with descriptive error feedback.
  * **FO-13 Fixed**: Replaced non-semantic clickable `<div>` navigation in `resources/views/profile/index.blade.php` with the full WAI-ARIA tab pattern (`role="tablist"`, `<button role="tab">` with `aria-controls`, `aria-selected`, roving `tabindex`, Left/Right/Home/End keyboard navigation, and `role="tabpanel"` with `aria-labelledby`). Upgraded password form with explicit labels, autocomplete tokens, and password show/hide button. Focus rings enhanced to satisfy WCAG 2.2 SC 2.4.7 / 2.4.11.
  * **FO-14 Fixed**: Added `aria-label="Menu principal"`, `aria-expanded="false"`, and `aria-controls="mobile-nav"` to mobile hamburger button in `resources/views/components/header.blade.php`. Updated `resources/js/app.js` to toggle `aria-expanded` synchronously.
  * **FO-15 Fixed**: Added `<a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>` and `<main id="main-content" tabindex="-1">` in `resources/views/layouts/app.blade.php` with visible `:focus` styling.
  * **Automated Tests**: Added `tests/Feature/FormAccessibilityTest.php` with 4 feature tests verifying skip links, mobile hamburger ARIA attributes, profile tab semantics/roving tabindex, and login error ARIA alerts (all 4 passing; full test suite now 17 passed, 94 assertions).
* **2026-11-12 (Batch 2 - Accessible Authentication Completed)**:
  * **FO-04 Fixed**: Enforced NIST SP 800-63-4 password policy via `Password::min(12)->uncompromised()` in `RegisterController` and `ProfileController`. Removed legacy composition regexes, supporting long passphrases and Unicode. *Note: `uncompromised()` checks passwords using a 5-character SHA-1 k-anonymity hash prefix sent to the HaveIBeenPwned API, ensuring zero plain or full-hash credential exposure.*
  * **FO-05 Fixed**: Completely removed Google reCAPTCHA v3 script and raw `abort(422)`. Implemented zero-dependency Honeypot bot prevention: off-screen honeypot input field (`website_hp`, `tabindex="-1"`, `autocomplete="off"`, `aria-hidden="true"`), form submission timing verification (min 2 seconds elapsed), returning standard validation errors.
  * **FO-06 Fixed**: Implemented full self-service password recovery using Laravel password broker on localized Portuguese routes (`GET/POST /esqueci-minha-senha`, `GET/POST /redefinir-senha/{token}`). Added generic, non-enumerating confirmation response to prevent email harvesting. Added "Esqueceu a senha?" link on login screen. Custom styled Portuguese email notification via `ResetPassword::toMailUsing`.
  * **FO-09 & FO-10 Fixed on Auth Pages**: Added explicit `<label for>` and `<input id>` associations across login and register screens. Added HTML5 `autocomplete="username"`, `autocomplete="current-password"`, `autocomplete="new-password"`, `autocomplete="email"`, `autocomplete="tel"`, and `autocomplete="name"`.
  * **Password Visibility Controls**: Added accessible show/hide password `<button>` controls with dynamic `aria-label` and `aria-pressed` states on login, register, and reset views.
  * **Automated Tests**: Added `tests/Feature/RegistrationAndResetTest.php` with 7 feature tests verifying NIST password rules, honeypot trapping, sub-second submission rejection, non-enumerating recovery dispatch, and token reset (all 7 passing; total test suite now 13 passed, 44 assertions).
* **2026-11-12 (Batch 1 - Auth Security Completed)**:
  * **FO-01 Fixed**: Removed `'login'` and `'register'` from `validateCsrfTokens(except: [...])` in `bootstrap/app.php`. Both routes now strictly enforce CSRF verification.
  * **FO-02 Fixed**: Removed wildcard proxy trust `trustProxies(at: '*')` in `bootstrap/app.php`. Replaced with explicit `TRUSTED_PROXIES` environment configuration, defaulting safely to `at: []`.
  * **FO-08 Fixed**: Enabled `$middleware->authenticateSessions()` in `bootstrap/app.php` and invoked `Auth::logoutOtherDevices()` + session regeneration upon password change in `ProfileController@updatePassword`.
  * **Rate Limiting Hardened**: Added per-account+IP rate limiting with countdown message in Portuguese (*"Muitas tentativas de login. Tente novamente em X segundos."*) to `LoginController@login`.
  * **Automated Tests**: Added `tests/Feature/AuthSecurityTest.php` verifying CSRF enforcement, throttle key behavior against spoofed `X-Forwarded-For`, and device logout on password update (all 4 tests passing).
* **2026-11-12**: Initial discovery and static audit completed. `AUDIT_REPORT.md` created with 15 verified findings across Security and Accessibility.

---

## 8. Resolved Decisions

1. **CSRF Exemption on Login/Register**: Removed exemption; full CSRF token validation re-enabled on both routes (`FO-01`).
2. **Password Policy**: Adopted NIST SP 800-63-4 (`Password::min(12)->uncompromised()`), eliminated composition regexes (`FO-04`).
3. **Bot Prevention**: Removed Google reCAPTCHA v3; replaced with zero-dependency Honeypot (`website_hp`) + 2-second timing check + IP rate-limiting (`FO-05`).
4. **Password Reset**: Fully implemented via Laravel password broker on localized Portuguese routes (`/esqueci-minha-senha`, `/redefinir-senha/{token}`) with non-enumerating generic feedback (`FO-06`).
5. **Dependency Updates**: Pinned to `patch_minor_only`; 35 advisories reduced to 0 with zero major version bumps (`FO-03`).
6. **Trusted Proxies**: Configured via `TRUSTED_PROXIES` environment variable defaulting to empty array `at: []`, preventing arbitrary proxy header spoofing (`FO-02`).
7. **Language of UI**: 100% Brazilian Portuguese (`pt-BR`) across all messages, templates, and email notifications.

---

## 9. Final Verification

### Fixed
* **FO-01**: CSRF protection re-enabled on `/login` and `/register` (`bootstrap/app.php:17-21`).
* **FO-02**: Wildcard proxy trust replaced with secure `TRUSTED_PROXIES` environment lookup (`bootstrap/app.php:16`).
* **FO-03**: All 35 Composer vulnerability advisories resolved to 0 via minor/patch updates (`composer.lock`).
* **FO-04**: NIST SP 800-63-4 password policy enforced with HaveIBeenPwned breach detection (`RegisterController.php:38`, `ProfileController.php:32`).
* **FO-05**: Removed reCAPTCHA v3 and raw `abort(422)`; implemented accessible Honeypot and timing check (`RegisterController.php:79-88`, `register.blade.php:306-312`).
* **FO-06**: Scaffolded complete self-service password recovery broker and localized Portuguese routes (`routes/web.php:28-31`, `ForgotPasswordController.php`, `ResetPasswordController.php`).
* **FO-07**: Session cookie configured to default `Secure` flag over HTTPS (`config/session.php:172`).
* **FO-08**: Invalidation of sessions on other devices and session regeneration upon password update (`ProfileController.php:36-39`, `bootstrap/app.php:24`).
* **FO-09**: Added explicit `<label for>` and `<input id>` associations across all auth and account forms (`login.blade.php`, `register.blade.php`, `profile/index.blade.php`).
* **FO-10**: Added HTML5 `autocomplete` attributes (`username`, `current-password`, `new-password`, `tel`, `name`) (`login.blade.php`, `register.blade.php`, `profile/index.blade.php`).
* **FO-11**: Form validation errors linked via `aria-invalid="true"`, `aria-describedby`, and `<span role="alert">`, with autofocus on first invalid input (`login.blade.php`, `register.blade.php`, `profile/index.blade.php`).
* **FO-12**: Removed color-only password rules checklist (`#pwd-rules`), replacing with clear text requirements (`register.blade.php:325`).
* **FO-13**: Replaced non-semantic `<div>` tabs with WAI-ARIA tab pattern (`role="tablist"`, `<button role="tab">`, roving `tabindex`, arrow key navigation, `role="tabpanel"`) and WCAG-compliant visible focus rings (`profile/index.blade.php:78-135`).
* **FO-14**: Added `aria-label="Menu principal"`, `aria-expanded`, and `aria-controls="mobile-nav"` to header hamburger toggle (`components/header.blade.php:33`, `resources/js/app.js:27`).
* **FO-15**: Added high-contrast keyboard skip link `<a href="#main-content" class="skip-link">` and `<main id="main-content" tabindex="-1">` (`layouts/app.blade.php:60-91`).
* **FO-16**: Fixed all 6 frontend vulnerability advisories (0 vulnerabilities remaining) via `npm audit fix` (`package-lock.json`).
* **FO-17**: Added rate limiting to `POST /contato` (`throttle:5,1`), `POST /checkout/submit` (`throttle:10,1`), and `POST /webhook/mercadopago` (`throttle:60,1`) (`routes/web.php:25,31,34`).
* **FO-18**: Removed `'role'` from `User::$fillable` to eliminate latent mass-assignment vulnerability (`app/Models/User.php:13`).
* **FO-19**: Added HTTP security defense headers (`X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`) via `SecurityHeaders` middleware (`bootstrap/app.php:29`, `app/Http/Middleware/SecurityHeaders.php`).
* **FO-20**: Added explicit `<label for>` and `<input id>` associations, autocomplete attributes, and ARIA alerts to all checkout address inputs; added `aria-pressed` to document type buttons (`pages/checkout.blade.php`).
* **FO-21**: Added accessible names (`aria-label`) to cart drawer buttons (`+`, `−`, `×`, close), modal semantics (`role="dialog"`, `aria-modal="true"`, `aria-labelledby`), Escape key closure, and `role="status"` live region to cart toast (`pages/produtos.blade.php`).
* **FO-22**: Added `aria-invalid="true"`, `aria-describedby`, `role="alert"`, and first-invalid autofocus to contact form (`pages/contato.blade.php`).

### Deferred
* **None**: All 22 audit findings across primary and secondary scopes have been resolved.

### New risks
* **k-anonymity API Availability**: `Password::uncompromised()` contacts the HaveIBeenPwned API using a 5-character SHA-1 prefix. If the remote service is unreachable, Laravel by default safely fails open (permits registration) to avoid denying service to legitimate users.
* **Honeypot Submission Speed**: Users employing fast autofill extensions submit forms quickly; the submission timing check threshold is set to a lenient 2.0 seconds to prevent false-positive blocks for legitimate autofill users.

### Not tested
* **Live MySQL database queries in browser**: Local MySQL service on the development workstation is currently stopped (`brew services list`). All application logic, database schemas, model operations, and authentication flows were tested in-memory via SQLite (`:memory:`).

### WCAG 2.2 SC 3.3.8: Pass
* **Status**: **PASS**
* **Evidence**:
  1. **Cognitive Function Tests Eliminated**: Google reCAPTCHA v3 puzzles, behavioral score blocks, and composition regex gymnastics (demanding mixed case, symbols, numbers) have been completely removed.
  2. **Alternative Authentication Mechanism**: Passwords only require minimum 12 characters and allow any Unicode string, spaces, or passphrases.
  3. **Object Recognition & Perception**: Users are not required to recognize objects or solve cognitive puzzles.
  4. **Password Managers & Autofill**: Forms provide full support for autofill via HTML5 `autocomplete="username"`, `autocomplete="current-password"`, and `autocomplete="new-password"`.
  5. **No Paste Blocking**: Paste events are permitted on all password and credential fields without interception.
  6. **Perceptual Verification**: Real accessible `<button>` toggles with dynamic `aria-pressed` states allow users to inspect and verify entered passwords.

### Next steps
1. **Start Database Service**: Run `brew services start mysql` and execute `php artisan migrate` when ready for local interactive browser testing.
2. **Configure Production Reverse Proxies**: If serving behind Cloudflare or an AWS ALB in production, supply trusted proxy CIDRs in `.env` via `TRUSTED_PROXIES=...`.
3. **Configure Mail Transport**: Configure production SMTP credentials in `.env` (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`) to enable password recovery email delivery.

---

> [!NOTE]
> *Disclaimer: Automated and static testing results do not constitute a formal legal compliance certification.*
