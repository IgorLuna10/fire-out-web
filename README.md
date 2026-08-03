# Fire Out - WEB

Fire Out - WEB is a Laravel application for Fire Out. It replaces the PHP website.

## Features

- **Routes**: URLs defined in `routes/web.php`.
- **Controllers**: Logic for forms and requests.
- **Views**: Blade templates for page structure.
- **Assets**: Scripts and styles compiled with Vite.

## Setup

### Prerequisites

- PHP
- Composer
- Node.js

### Steps

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run dev
php artisan serve
```
