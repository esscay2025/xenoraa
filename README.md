# Gopi K — Portfolio & Blog System

Welcome to the repository for **gopi.blog**, the professional portfolio, blog, and job listing system of Gopi K (Founder & Tech Entrepreneur, Go Esscay Solutions).

Built on **Laravel 10**, **Blade**, **Tailwind CSS**, and **MySQL**, this system serves as Gopi's central hub for business consulting, professional writing, and enterprise automation insights.

---

## 🚀 Key Features & Modules

* **Public Portfolio:** Sleek, modern black/white themed portfolio showcasing Gopi's 14+ year journey, skills, and career timeline.
* **Writing & Blog System:** Interactive blog platform covering enterprise architecture, business automation, AI, and ethical hacking.
* **Newsletter Subscription:** Fully-integrated email subscription system on the Homepage, About page, and Footer to keep subscribers updated.
* **Social Login (OAuth):** Seamless registration and login using Google and Facebook accounts.
* **Job Board:** View open roles and consulting opportunities at Go Esscay Solutions, with resume upload and application tracking.
* **Expense Manager:** Personal and business expense tracking with built-in approval workflows.
* **User Management:** Role-Based Access Control (RBAC) supporting Admin, Staff, and Visitor roles.
* **API Integration:** Ready for future mobile app (Android/iOS) integration via Laravel Sanctum.

---

## 🛠️ Tech Stack

* **Backend:** PHP 8.2+ / Laravel 10
* **Frontend:** Blade Templates, Alpine.js, Tailwind CSS, Vite
* **Database:** MySQL 8.0+
* **Authentication:** Laravel Breeze & Socialite (Google, Facebook)
* **Hosting:** Hostinger VPS / Ubuntu Linux / Nginx

---

## 📖 Developer Documentation

We have compiled comprehensive guides to help you set up, develop, and deploy this project:

* **[Developer Setup & Deployment Guide (docs/DEVELOPMENT_GUIDE.md)](docs/DEVELOPMENT_GUIDE.md):** Step-by-step instructions on setting up your local environment, git branching strategy, manual deployment, and database migrations.
* **[Production Deployment Guide (docs/DEPLOYMENT.md)](docs/DEPLOYMENT.md):** Hostinger-specific deployment steps, directory structure setups, and symlink configurations.
* **[CI/CD Workflow (docs/ci-cd-workflow.yml)](docs/ci-cd-workflow.yml):** Configuration template for automated testing, staging deployment, and production release pipelines via GitHub Actions.

---

## ⚡ Quick Start (Local Development)

```bash
# 1. Clone the repository
git clone https://github.com/esscay2025/gopi-portfolio.git
cd gopi-portfolio

# 2. Install dependencies
composer install
npm install

# 3. Setup environment variables
cp .env.example .env
php artisan key:generate

# 4. Run migrations & seed data
php artisan migrate --seed
php artisan storage:link

# 5. Run development servers
php artisan serve
npm run dev
```

Visit [http://localhost:8000](http://localhost:8000) to view the site locally.

---

## 🔑 Default Admin Credentials

* **Email:** `gopi@outlook.in`
* **Password:** `Admin@2025!` *(Change this password immediately after first login in production)*

---

## 🔒 License & Ownership

Copyright © 2026 Gopi K — Go Esscay Solutions. All rights reserved.
This repository is private and proprietary. Unauthorized copying, distribution, or use is strictly prohibited.
