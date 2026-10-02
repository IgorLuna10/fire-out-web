# Fire Out Web Platform 🚒

> **TL;DR (FR)** : Application web full-stack développée avec **PHP 8.3 & Laravel**, conçue pour remplacer et moderniser un ancien site web PHP statique en une plateforme e-commerce et de gestion de services. Intègre un catalogue dynamique de produits de sécurité incendie, un tunnel de commande (*checkout*) avec passerelle de paiement (Mercado Pago), un espace d'administration complet et un pipeline d'assets moderne avec Vite.

A full-stack web application built to modernize an institutional and catalog website into a dynamic, production-ready e-commerce platform. Built with PHP 8.3, Laravel, Blade templates, and Vite.

---

## 📸 Architecture & Features

```text
┌────────────────────────────────────────────────────────┐
│ Fire Out Web Platform (Laravel MVC)                    │
├─────────────────┬──────────────────────────────────────┤
│ 🛒 Catalog & UI │ • Dynamic products & services list   │
│                 │ • Category filters & fire-door specs │
│ 💳 Checkout     │ • Cart management & order workflow   │
│                 │ • Mercado Pago payment integration   │
│ 🛡 Admin Panel  │ • Product CRUD & inventory updates   │
│                 │ • Contact requests & customer leads  │
└─────────────────┴──────────────────────────────────────┘
```

- **Product & Services Catalog**: Full relational catalog for fire safety equipment (`ProdutosController`, `ServicosController`, `PortaCortaFogoController`).
- **Payment & Checkout Flow**: Shopping cart, order processing, and payment integration via Mercado Pago SDK (`CheckoutController`).
- **Admin Dashboard**: Protected management panel for catalog maintenance, lead monitoring, and customer inquiry management (`AdminController`).
- **Modern Asset Bundling**: Fast page loads and modular styles compiled through Vite and modern CSS.
- **Robust Validation & Security**: CSRF protection, request validation, authentication, and transactional integrity.

---

## 🛠 Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | PHP 8.3, Laravel Framework |
| **Frontend & Templating** | Blade Templates, Vite, Modern CSS |
| **Database** | MySQL / SQLite (Eloquent ORM) |
| **Payment Gateway** | Mercado Pago SDK (`mercadopago/dx-php`) |
| **Mailing** | Symfony Mailer |
| **Tooling & Code Quality** | Composer, NPM, Laravel Pint |

---

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.3 with standard extensions (`pdo`, `mbstring`, `openssl`, `curl`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM

### Setup & Run Locally

1. **Clone the repository:**
   ```bash
   git clone https://github.com/IgorLuna10/fire-out-web.git
   cd fire-out-web
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Configure your local `DB_DATABASE` and mail driver in `.env`)*

4. **Run migrations (optional / local SQLite or MySQL):**
   ```bash
   php artisan migrate
   ```

5. **Start frontend asset compiler and local server:**
   ```bash
   npm run dev &
   php artisan serve
   ```
   The application will be live at `http://127.0.0.1:8000`.

---

## 💡 What I Learned

- **Legacy Migration Strategy**: Transitioned an unstructured procedural PHP site into a structured MVC architecture using Laravel conventions, models, and clean separation of concerns.
- **Payment Flow Integration**: Handled real-world third-party checkout states, webhooks, and payment feedback using Mercado Pago.
- **Full-Stack Asset Pipelines**: Configured Vite within Laravel Blade for hot module replacement and optimized production asset minification.
