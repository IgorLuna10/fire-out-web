# 🔥 Fire Out — Web Application (Modernized Version)

This is the modernized, Laravel-based web application for **Fire Out**, replacing the previous HTML/CSS/PHP institutional website ([fire-out](https://github.com/IgorLuna10/fire-out)).

---

## 🚀 Overview

**Fire Out** is a specialized company in fire prevention and firefighting equipment with over 30 years of market presence in Brazil. 

This repository contains the **modernized version** built with:
- **PHP 8.2+**
- **Laravel 11** — Modern MVC framework for backend logic and APIs
- **Vite & Tailwind CSS** — Highly optimized frontend and sleek design
- **SMTP Integration** — Reliable email dispatch for the contact system

---

## 📁 Project Structure

This Laravel application provides a robust structure compared to the legacy static site:
- **Routes**: Clean, SEO-friendly routing defined in `routes/web.php`
- **Controllers**: Handled dynamically for contact forms and page queries
- **Views**: Structured Blade templates for modular headers, footers, and components
- **Assets**: Compiled dynamically using Vite for high performance

---

## ⚙️ Setup & Installation

To run this application locally:

### 1. Clone the Repository
```bash
git clone git@github.com:IgorLuna10/fire-out-web.git
cd fire-out-web
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the `.env.example` file to `.env` and configure your database and SMTP credentials:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Build Assets & Run Development Server
```bash
npm run dev
php artisan serve
```

---

© 2026 Fire Out. All rights reserved.
