# Gopi K — Personal Portfolio Application

A comprehensive personal portfolio web application built with **Laravel (PHP)** and **MySQL**, featuring a blog, job portal, expense manager, and role-based user management. Designed with a sleek **black/white theme** (70% black, 30% white) and built for continuous enhancement including future mobile app (Android/iOS) integration.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | PHP 8.1+ with Laravel 10 |
| Database | MySQL (phpMyAdmin compatible) / SQLite (local dev) |
| Frontend | Blade Templates, Tailwind CSS, Vite |
| Authentication | Laravel Breeze |
| File Storage | Laravel Storage (local/S3 ready) |
| API | Laravel Sanctum (mobile app ready) |
| CI/CD | GitHub Actions |

---

## Modules

- **Public Portfolio** — Hero, skills, experience, blog, jobs
- **Blog** — Create/manage posts, visitor comments & reviews
- **Job Portal** — Post jobs, manage applications, resume uploads
- **Expense Manager** — Personal & business expense tracking with approval workflow
- **User Management** — RBAC with Admin, Staff, and Visitor roles

---

## User Roles

| Role | Access |
|---|---|
| **Admin** | Full access to all modules and user management |
| **Staff** | Expense Manager (own expenses), Blog/Jobs (read) |
| **Visitor** | Blog (read + comment), Jobs (read + apply) |

---

## Default Admin Credentials

```
Email:    gopi@outlook.in
Password: @biSou20717
```

> **Change this password immediately after first login in production.**

---

## Installation

```bash
git clone https://github.com/YOUR_USERNAME/gopi-portfolio.git
cd gopi-portfolio
composer install
npm install
cp .env.example .env
php artisan key:generate
# Configure DB in .env, then:
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

---

## MySQL Setup

```sql
CREATE DATABASE gopi_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gopi_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON gopi_portfolio.* TO 'gopi_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## CI/CD Pipeline

GitHub Actions runs on every push to `main` or `develop`:
1. **Code Quality** — PHP syntax checking
2. **Tests** — PHPUnit with MySQL service
3. **Deploy Staging** — Triggered on `develop` branch
4. **Deploy Production** — Triggered on `main` branch

---

## Mobile App (Future Phase)

Built API-ready with Laravel Sanctum. Future mobile app endpoints:
- `GET /api/posts` — Blog posts
- `GET /api/jobs` — Job listings
- `POST /api/jobs/{slug}/apply` — Job applications
- `POST /api/auth/login` — Authentication

---

## License

Private — All rights reserved by Gopi K / Go Esscay Solutions.
