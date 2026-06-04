<div align="center">

# Xenoraa — Build Your Digital Identity

**The all-in-one SaaS platform for professionals, freelancers, and businesses to create stunning digital portfolios, manage clients, publish content, and sell products — all under one roof.**

[![Version](https://img.shields.io/badge/version-v2.2.0-7c3aed?style=flat-square)](https://github.com/esscay2025/xenoraa/releases)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-Proprietary-1e1e2e?style=flat-square)](LICENSE)

**[Live Site](https://xenoraa.com)** · **[Gopi's Portfolio](https://gopi.blog)** · **[Super Admin](https://xenoraa.com/superadmin/dashboard)**

</div>

---

## Overview

Xenoraa is a multi-tenant SaaS platform built on Laravel. Every user gets their own professional digital presence at `xenoraa.com/{username}`, with the option to map a custom domain (e.g., `gopi.blog`). The platform includes a full suite of business tools — blog, CRM, AI chatbot, e-commerce, newsletter, calendar, and more — all managed from a single admin dashboard.

> **gopi.blog** is the first live tenant on Xenoraa, serving as both a real-world showcase and a proof-of-concept for the platform's multi-tenant architecture.

---

## Live URLs

| URL | Description |
|---|---|
| https://xenoraa.com | Xenoraa marketing homepage |
| https://xenoraa.com/xenoraa/features | Features overview |
| https://xenoraa.com/xenoraa/pricing | Subscription plans with Razorpay |
| https://xenoraa.com/xenoraa/showcase | Real user profiles |
| https://xenoraa.com/xenoraa/blog | SEO & content marketing |
| https://xenoraa.com/login | Xenoraa login |
| https://xenoraa.com/register | New user signup with onboarding |
| https://xenoraa.com/superadmin/dashboard | Super Admin dashboard |
| https://xenoraa.com/gopi | Gopi's public profile |
| https://gopi.blog | Gopi's custom domain portfolio |

---

## Features

### For Users (Tenants)

| Module | Description |
|---|---|
| **Portfolio** | Professional profile at `xenoraa.com/{username}` or custom domain |
| **Blog** | Full CMS with categories, SEO, featured images, and drafts |
| **AI Chatbot** | GPT-powered chatbot widget for visitor engagement and requirement gathering |
| **CRM & Leads** | Capture leads from chatbot, manage pipeline, send reply emails with PDF proposals |
| **Chat Monitor** | View and reply to all visitor conversations from the admin dashboard |
| **E-commerce** | Product catalog, categories, reviews, and order management |
| **Newsletter** | Subscriber management and broadcast emails |
| **Calendar & Notes** | Event scheduling and personal notes |
| **Solutions Pages** | Dedicated pages for each service offering |
| **Custom Domain** | Map any domain to your Xenoraa profile (Professional/Business plan) |

### For Super Admin

| Module | Description |
|---|---|
| **Dashboard** | Platform-wide stats: users, revenue, MRR, subscriptions |
| **User Management** | View, impersonate, suspend, or delete any user |
| **Subscriptions** | Monitor active trials, paid plans, and churn |
| **Revenue** | Transaction history and Razorpay payment logs |
| **Domain Management** | Approve or reject custom domain mapping requests |
| **Platform Blog** | Manage Xenoraa's own marketing blog |
| **Showcase** | Curate featured user profiles for the showcase page |
| **Email Broadcasts** | Send platform-wide announcements |
| **Activity Logs** | Full audit trail of user and admin actions |
| **Analytics** | Traffic, signups, and engagement metrics |
| **Settings** | Platform configuration, pricing, and feature flags |

---

## Multi-Tenant Architecture

Xenoraa uses a **shared database, shared application** multi-tenant model with domain-based routing:

```
Request → Nginx → Laravel
    ↓
TenantMiddleware resolves tenant:
  xenoraa.com/{username}  → find user by username
  gopi.blog               → find user by custom_domain
    ↓
Route to correct controller with tenant context
```

### Domain Routing Logic

| Domain | Behaviour |
|---|---|
| `xenoraa.com` | Shows Xenoraa marketing site (Home, Features, Pricing, etc.) |
| `xenoraa.com/login` | Xenoraa-branded login page |
| `xenoraa.com/{username}` | Tenant's public profile |
| `gopi.blog` | Resolves to Gopi's tenant account (custom domain) |
| `gopi.blog/login` | Portfolio-styled login page |
| `gopi.blog/admin` | Gopi's admin dashboard |

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 10, PHP 8.2 |
| **Frontend** | Blade templates, Vanilla JS, CSS3 |
| **Database** | MySQL 8 |
| **Web Server** | Nginx with SSL (Let's Encrypt) |
| **Email** | Hostinger SMTP (support@xenoraa.com) |
| **AI** | OpenAI GPT-4o-mini via openai-php/laravel |
| **Payments** | Razorpay (test + live keys) |
| **PDF Generation** | barryvdh/laravel-dompdf |
| **Hosting** | VPS at 69.62.75.225 |
| **Version Control** | GitHub (esscay2025/xenoraa) |

---

## Subscription Plans

| Plan | Monthly | Yearly | Key Features |
|---|---|---|---|
| **Starter** | ₹499 | ₹4,999 | Portfolio, Blog (20 posts), Basic CRM |
| **Professional** | ₹999 | ₹9,999 | + Custom domain, AI Chatbot, Newsletter, Full CRM |
| **Business Pro** | ₹1,999 | ₹19,999 | + E-commerce, 5 team members, Priority support |

All plans include a **14-day free trial** on signup.

---

## Getting Started (Local Development)

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 8
- Node.js 18+

### Installation

```bash
# Clone the repository
git clone https://github.com/esscay2025/xenoraa.git
cd xenoraa

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env (see Environment Variables section below)

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed

# Seed chatbot training data
php artisan db:seed --class=ChatbotTrainingSeeder

# Link storage
php artisan storage:link

# Build assets
npm run build

# Start development server
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) to view the site locally.

---

## Environment Variables

```env
# Application
APP_NAME=Xenoraa
APP_URL=https://xenoraa.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail (Hostinger SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=support@xenoraa.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=support@xenoraa.com
MAIL_FROM_NAME="Xenoraa"

# OpenAI (for AI Chatbot)
OPENAI_API_KEY=sk-proj-...

# Razorpay (Payment Gateway)
RAZORPAY_KEY_ID=rzp_test_...
RAZORPAY_KEY_SECRET=your_secret

# Xenoraa Platform
XENORAA_MAIN_DOMAIN=xenoraa.com
SUPERADMIN_EMAILS=support@xenoraa.com
```

---

## Deployment

The production server runs Nginx + PHP-FPM. To deploy:

```bash
# On the VPS
cd /var/www/gopi.blog/gopi-portfolio
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Nginx Configuration

Two server blocks are configured on the VPS:
- `gopi.blog` → `/var/www/gopi.blog/gopi-portfolio/public`
- `xenoraa.com` → `/var/www/gopi.blog/gopi-portfolio/public`

Both point to the same Laravel application. Domain-aware routing is handled by `TenantMiddleware` and the root route controller.

---

## Super Admin Access

| Field | Value |
|---|---|
| URL | https://xenoraa.com/superadmin/dashboard |
| Email | support@xenoraa.com |
| Role | superadmin |

---

## Project Structure

```
xenoraa/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Tenant admin dashboard controllers
│   │   │   ├── Auth/           # Authentication (domain-aware)
│   │   │   ├── Public/         # Public-facing controllers (chatbot, blog, etc.)
│   │   │   ├── SuperAdmin/     # Super admin controllers
│   │   │   └── Xenoraa/        # Marketing site, onboarding, payments, tenant routing
│   │   └── Middleware/
│   │       ├── TenantMiddleware.php      # Domain-based tenant resolution
│   │       └── SuperAdminMiddleware.php  # Super admin access guard
│   └── Models/
├── config/
│   └── xenoraa.php             # Platform configuration
├── database/
│   ├── migrations/
│   └── seeders/
│       └── ChatbotTrainingSeeder.php  # 63 AI training entries
├── docs/
│   ├── DEVELOPMENT_GUIDE.md    # Full developer setup guide
│   └── DEPLOYMENT.md           # Production deployment guide
├── resources/views/
│   ├── admin/                  # Tenant admin dashboard views
│   ├── auth/                   # Login, register (domain-aware)
│   ├── errors/                 # Custom 404, 500, 419, 403, 503 pages
│   ├── layouts/                # app, admin, superadmin, xenoraa layouts
│   ├── portfolio/              # Tenant portfolio pages
│   ├── superadmin/             # Super admin views
│   ├── tenant/                 # Public tenant profile views
│   └── xenoraa/                # Marketing site views + onboarding
└── routes/
    └── web.php                 # All routes (marketing, admin, superadmin, tenant)
```

---

## Developer Documentation

| Document | Description |
|---|---|
| [docs/DEVELOPMENT_GUIDE.md](docs/DEVELOPMENT_GUIDE.md) | Local setup, git branching, migrations, SMTP config |
| [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) | VPS deployment, Nginx config, SSL setup |
| [docs/ci-cd-workflow.yml](docs/ci-cd-workflow.yml) | GitHub Actions CI/CD template |

---

## Changelog

### v2.2.0 — Super Admin, Error Pages, Razorpay & Email
- Super admin user (`support@xenoraa.com`) created and configured
- All super admin views built (subscriptions, revenue, domains, analytics, etc.)
- Custom Xenoraa-styled error pages (404, 500, 419, 403, 503)
- Razorpay payment gateway integrated on pricing page
- All emails now send from `support@xenoraa.com`
- Fixed 500 cascade error on 404 pages and tenant profile view

### v2.1.0 — Xenoraa Login, Onboarding & Root Homepage
- `xenoraa.com` root now shows Xenoraa marketing homepage
- Xenoraa-branded login and register pages
- Domain-aware login routing (xenoraa.com vs custom domains)
- 3-step user onboarding with username selection and 14-day trial

### v2.0.0 — Xenoraa SaaS Launch
- Converted gopi-portfolio to Xenoraa multi-tenant SaaS
- Marketing site: Home, Features, Pricing, Showcase, Blog, Get Started
- Multi-tenant routing: `xenoraa.com/{username}` and custom domain mapping
- Super Admin dashboard foundation
- `xenoraa.com` Nginx config and SSL certificate

### v1.12.0 — Chatbot, Profile & Admin Fixes
- Fixed AI chatbot CSRF issue (now works for all visitors)
- Chat Monitor admin reply feature
- Collapsible training categories
- Profile edit page fixed (custom layout)
- Calendar & Notes added to admin dashboard

### v1.11.0 — Chatbot Training & E-commerce
- 63 chatbot training entries (business analyst, sales, requirement gathering)
- E-commerce route name fixes
- CRM lead view and edit
- Reply email with PDF attachment
- Auto-subscribe new users to newsletter

### v1.10.0 — Multi-Fix Release
- AI chatbot model fixed (gpt-4o-mini)
- Blog image display (local + external URLs)
- Admin Calendar & Notes sidebar link
- Footer newsletter mobile responsiveness
- Homepage Solutions section

### v1.9.0 — Email & SMTP
- Hostinger SMTP configured (support@gopi.blog)
- SPF, DKIM, DMARC DNS records verified

---

## License

Proprietary — All rights reserved. © 2025–2026 Xenoraa / Go Esscay Solutions.

---

<div align="center">
  <strong>Built with ❤️ by <a href="https://gopi.blog">Gopi K</a> — Founder, Go Esscay Solutions</strong><br>
  <a href="https://xenoraa.com">xenoraa.com</a> · <a href="mailto:support@xenoraa.com">support@xenoraa.com</a>
</div>
