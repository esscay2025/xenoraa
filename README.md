<div align="center">

# Xenoraa

**All-in-One Business Platform for Modern Professionals**

[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-336791?style=flat&logo=postgresql&logoColor=white)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=flat)](LICENSE)

[xenoraa.com](https://xenoraa.com) · [support@xenoraa.com](mailto:support@xenoraa.com)

</div>

---

## Overview

Xenoraa is a **multi-tenant SaaS platform** that gives businesses a complete digital presence and business management suite under one roof. Each tenant gets a professional portfolio site, a full-featured CRM, inventory management, e-commerce, point of sale, AI tools, and more — all accessible from a single admin dashboard.

Xenoraa is built on Laravel 10 with a shared-database multi-tenant architecture, domain-aware routing, and a subscription model powered by Razorpay.

---

## Live URLs

| URL | Description |
|---|---|
| [xenoraa.com](https://xenoraa.com) | Xenoraa marketing homepage |
| [xenoraa.com/xenoraa/features](https://xenoraa.com/xenoraa/features) | Platform features overview |
| [xenoraa.com/xenoraa/pricing](https://xenoraa.com/xenoraa/pricing) | Subscription plans with Razorpay |
| [xenoraa.com/xenoraa/showcase](https://xenoraa.com/xenoraa/showcase) | Featured tenant profiles |
| [xenoraa.com/xenoraa/blog](https://xenoraa.com/xenoraa/blog) | Xenoraa platform blog |
| [xenoraa.com/register](https://xenoraa.com/register) | New tenant signup with onboarding |
| [xenoraa.com/login](https://xenoraa.com/login) | Tenant login |
| [xenoraa.com/superadmin/dashboard](https://xenoraa.com/superadmin/dashboard) | Super Admin dashboard |

---

## Platform Modules

### Tenant Admin Dashboard

Each tenant gets a fully-featured admin panel at `{domain}/admin` covering the following modules:

#### CRM — Sales

| Module | Features |
|---|---|
| **Leads** | Listing with checkboxes, row-click navigation, 3-dot bulk menu (Create Task, Delete, Export CSV), Lead View page with full details, clone, print preview, delete |
| **Contacts** | Same listing/view pattern as Leads; linked to Accounts and Deals |
| **Accounts** | Company-level CRM records with linked contacts, deals, and activities |
| **Deals** | Pipeline management with stage tracking, linked account/contact, and value |
| **Forecasts** | Sales forecasting based on deal pipeline and expected close dates |

#### CRM — Activities

| Module | Features |
|---|---|
| **Tasks** | Task management across all CRM records with priority, status, and due dates |
| **Meetings** | Meeting scheduling with attendees and linked CRM records |
| **Calls** | Call log with duration, outcome, and linked records |

#### CRM — Projects

| Module | Features |
|---|---|
| **Projects Listing** | Checkboxes, row-click navigation, progress bar, priority badge, linked account/deal, overdue indicator, 3-dot bulk menu |
| **Project View — Overview** | Project details grid, budget vs cost, progress bar, stats cards (tasks, completed, open issues, hours logged), milestone progress, recent tasks |
| **Project View — Tasks** | Inline quick-add form + Kanban board (To Do / In Progress / Testing / Completed) with per-card status updates |
| **Project View — Milestones** | Inline add form + milestone cards with linked task progress bars and overdue detection |
| **Project View — Issues** | Report Issue form + table with inline severity and status updates |
| **Project View — Time Log** | Log time against project or task; total/estimated/weekly hours stat cards |
| **Project View — Notes** | Add notes with author and timestamp; threaded note history |

#### Inventory

| Module | Features |
|---|---|
| **Price Books** | Custom pricing tiers with product line items |
| **Quotes** | Full quote builder with line items, taxes, discounts; convert to Sales Order or Invoice |
| **Sales Orders** | SO management; convert to Invoice |
| **Purchase Orders** | PO management with vendor linking |
| **Invoices** | Invoice management with payment tracking; PDF export and email send |
| **Vendors** | Vendor directory with contacts, notes, and linked POs |

All inventory modules include: checkboxes, row-click navigation, 3-dot bulk menu, view pages with Notes / Activities / Attachments tabs, clone, export to PDF, send mail, and delete actions.

#### E-Commerce

| Module | Features |
|---|---|
| **Products** | Product catalog with images, categories, pricing, and stock |
| **Categories** | Hierarchical product categories |
| **Reviews** | Customer review management |
| **Store Config** | Storefront settings and integrations |

#### Point of Sale (POS)

| Module | Features |
|---|---|
| **POS Terminal** | Touch-friendly terminal with product grid, cart, and checkout |
| **Orders** | POS order history with itemised receipts |
| **Sessions** | Shift-based session management with opening/closing cash |
| **POS Reports** | Daily sales, top products, and session summaries |

#### AI Hub

| Module | Features |
|---|---|
| **AI Assistant** | GPT-4o-mini powered assistant for business queries |
| **Train AI** | Add custom training entries to personalise the chatbot |
| **AI Conversations** | Monitor and reply to all visitor chatbot conversations |

#### Site Builder

| Module | Features |
|---|---|
| **Page Manager** | Create and manage custom pages |
| **Menu Builder** | Configure site navigation |
| **Blog** | Full CMS with categories, SEO meta, featured images, and drafts |
| **Domain Config** | Map a custom domain to the tenant's Xenoraa profile |
| **Site Settings** | Theme, branding, contact info, and social links |

#### Support & Services

| Module | Features |
|---|---|
| **Cases** | Customer support ticket management |
| **Solutions** | Knowledge base articles linked to cases |
| **Services** | Service catalogue with descriptions and pricing |
| **Service Catalog** | Structured service offering pages |
| **Bookings** | Appointment and booking management |

#### Other Modules

| Module | Features |
|---|---|
| **Newsletter** | Subscriber management and broadcast email campaigns |
| **Calendar & Notes** | Event scheduling and personal notes |
| **Staff Users** | Invite and manage team members with role-based access |
| **Roles & Permissions** | Custom role definitions with granular module permissions |
| **Mail Config** | Per-tenant SMTP configuration |
| **Mail Templates** | Customisable email templates for all outbound emails |

---

### For Super Admin

| Module | Description |
|---|---|
| **Dashboard** | Platform-wide stats: tenants, revenue, MRR, active subscriptions |
| **User Management** | View, impersonate, suspend, or delete any tenant |
| **Subscriptions** | Monitor active trials, paid plans, and churn |
| **Revenue** | Transaction history and Razorpay payment logs |
| **Domain Management** | Approve or reject custom domain mapping requests |
| **Plan Modules** | Configure which modules are available per subscription plan |
| **Platform Blog** | Manage Xenoraa's own marketing blog |
| **Showcase** | Curate featured tenant profiles for the showcase page |
| **Theme Management** | Manage and preview available site themes |
| **Activity Logs** | Full audit trail of tenant and admin actions |
| **Analytics** | Traffic, signups, and engagement metrics |
| **Settings** | Platform configuration, pricing, and feature flags |

---

## Multi-Tenant Architecture

Xenoraa uses a **shared database, shared application** multi-tenant model with domain-based routing:

```
Request → Nginx → Laravel
    ↓
TenantMiddleware resolves tenant:
  xenoraa.com/{username}   → find user by username
  {custom-domain}.com      → find user by custom_domain column
    ↓
Route to correct controller with tenant context (user_id scoping)
```

### Domain Routing Logic

| Domain Pattern | Behaviour |
|---|---|
| `xenoraa.com` | Xenoraa marketing site (Home, Features, Pricing, Showcase, Blog) |
| `xenoraa.com/login` | Xenoraa-branded login page |
| `xenoraa.com/{username}` | Tenant's public portfolio profile |
| `{custom-domain}/login` | Portfolio-styled login page for that tenant |
| `{custom-domain}/admin` | Tenant's admin dashboard |

All tenant data is scoped by `user_id` and `tenant_owner_id` columns across every table. No cross-tenant data leakage is possible at the query level.

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 10, PHP 8.3 |
| **Frontend** | Blade templates, Vanilla JS, CSS3 |
| **Database** | PostgreSQL 16.14 |
| **Web Server** | Nginx 1.24 with SSL (Let's Encrypt) |
| **Email** | Hostinger SMTP (`support@xenoraa.com`) |
| **AI** | OpenAI GPT-4o-mini via `openai-php/laravel` |
| **Payments** | Razorpay (UPI, cards, net banking) |
| **PDF Generation** | `barryvdh/laravel-dompdf` |
| **Hosting** | Ubuntu VPS — 69.62.75.225 |
| **Version Control** | GitHub — `esscay2025/xenoraa` |

---

## Subscription Plans

| Plan | Monthly | Yearly | Key Features |
|---|---|---|---|
| **Starter** | ₹499 | ₹4,999 | Portfolio, Blog (20 posts), Basic CRM |
| **Professional** | ₹999 | ₹9,999 | + Custom domain, AI Chatbot, Newsletter, Full CRM |
| **Business Pro** | ₹1,999 | ₹19,999 | + E-commerce, POS, Projects, 5 team members, Priority support |

All plans include a **14-day free trial** on signup. Payment is processed via Razorpay at registration.

---

## Getting Started (Local Development)

### Prerequisites

- PHP 8.3+
- Composer
- PostgreSQL 16+
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

# Configure your .env (see Environment Variables section)

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

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=xenoraa
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# OpenAI
OPENAI_API_KEY=sk-...

# Razorpay
RAZORPAY_KEY=rzp_live_...
RAZORPAY_SECRET=...

# Mail (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=support@xenoraa.com
MAIL_PASSWORD=...
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=support@xenoraa.com
MAIL_FROM_NAME=Xenoraa
```

---

## Deployment

### Standard Deployment (Production)

```bash
cd /var/www/xenoraa/app

# Pull latest code
git pull origin main

# Run new migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Nginx Configuration

Two or more server blocks can be configured to point to the same Laravel application:

```nginx
server {
    server_name xenoraa.com www.xenoraa.com;
    root /var/www/xenoraa/app/public;
    # ... standard Laravel Nginx config
}
```

Domain-aware routing is handled entirely by `TenantMiddleware` — no separate Nginx configuration is needed per tenant.

---

## Super Admin Access

| Field | Value |
|---|---|
| URL | `https://xenoraa.com/superadmin/dashboard` |
| Email | `support@xenoraa.com` |
| Role | `superadmin` |

---

## Project Structure

```
xenoraa/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Tenant admin dashboard controllers
│   │   │   │   ├── CrmModuleController.php   # All CRM + Projects methods
│   │   │   │   ├── InventoryController.php   # Quotes, SO, PO, Invoices, Vendors
│   │   │   │   ├── PosController.php         # Point of Sale
│   │   │   │   └── DashboardController.php   # Tenant dashboard stats
│   │   │   ├── Auth/               # Authentication (domain-aware)
│   │   │   ├── Public/             # Public-facing (chatbot, blog, portfolio)
│   │   │   ├── SuperAdmin/         # Super admin controllers
│   │   │   └── Xenoraa/            # Marketing site, onboarding, payments
│   │   └── Middleware/
│   │       ├── TenantMiddleware.php           # Domain-based tenant resolution
│   │       └── SuperAdminMiddleware.php       # Super admin access guard
│   ├── Models/
│   │   ├── CrmProject.php                    # Project with progress accessor
│   │   ├── CrmProjectTask.php                # Task with milestone relationship
│   │   ├── CrmProjectMilestone.php           # Milestone with task progress
│   │   ├── CrmProjectIssue.php               # Issue tracking
│   │   ├── CrmProjectTimeLog.php             # Time logging
│   │   ├── CrmProjectNote.php                # Project notes
│   │   ├── CrmLead.php / CrmContact.php      # Sales CRM models
│   │   ├── CrmAccount.php / CrmDeal.php      # Account & deal models
│   │   ├── CrmQuote.php / CrmInvoice.php     # Inventory models
│   │   └── PosOrder.php / PosSession.php     # POS models
│   └── Services/
│       └── AiTenantContentService.php        # GPT-4o-mini content generation
├── config/
│   └── xenoraa.php                           # Platform config (plans, modules)
├── database/
│   ├── migrations/                           # 100+ migrations
│   └── seeders/
│       └── ChatbotTrainingSeeder.php         # 63 AI training entries
├── resources/views/
│   ├── admin/
│   │   ├── crm2/                             # CRM module views
│   │   │   ├── sales/                        # Leads, Contacts, Accounts, Deals
│   │   │   ├── projects/                     # Projects listing + view tabs
│   │   │   ├── activities/                   # Tasks, Meetings, Calls
│   │   │   └── inventory/                    # All inventory module views
│   │   └── pos/                              # POS terminal and reports
│   ├── auth/                                 # Login, register (domain-aware)
│   ├── errors/                               # Custom 404, 500, 419, 403, 503
│   ├── layouts/                              # admin, superadmin, xenoraa layouts
│   ├── superadmin/                           # Super admin views
│   ├── tenant/                               # Public tenant profile views
│   └── xenoraa/                              # Marketing site + onboarding
└── routes/
    └── web.php                               # All routes (marketing, admin, superadmin, tenant)
```

---

## Developer Documentation

| Document | Description |
|---|---|
| [docs/DEVELOPMENT_GUIDE.md](docs/DEVELOPMENT_GUIDE.md) | Local setup, git branching, migrations, SMTP config |
| [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) | VPS deployment, Nginx config, SSL setup |
| [AGENTS.md](AGENTS.md) | Server configuration, deployed modules log, and deployment commands |

---

## Changelog

### v4.11.0 — Enhanced Projects Module
- Projects listing: checkboxes, row-click navigation, progress bar, priority badge, linked account/deal, overdue indicator, 3-dot bulk menu
- Project view page: 6-tab layout (Overview, Tasks, Milestones, Issues, Time Log, Notes)
- Tasks tab: inline Add Task form + Kanban board (To Do / In Progress / Testing / Completed)
- Milestones tab: milestone cards with linked task progress bars and overdue detection
- Issues tab: Report Issue form with inline severity and status updates
- Time Log tab: total/estimated/weekly hours stat cards + log table
- Notes tab: note cards with author and timestamp
- New DB tables: `crm_project_milestones`, `crm_project_issues`, `crm_project_time_logs`, `crm_project_notes`
- 4 new Eloquent models, 22 new controller methods, 28 new routes

### v4.10.0 — Sales Sub-modules: Contacts, Accounts, Deals
- Contacts, Accounts, Deals listing pages: checkboxes, row-click navigation, 3-dot bulk menu (Create Task, Delete, Export CSV)
- View pages for all 3 modules: 3-dot action menu (Clone, Print Preview, Delete)
- Lead View page: fixed HTML span rendering in `{!! !!}` unescaped output
- 9 new controller methods + 9 new routes

### v4.9.x — Tenant Dashboard Redesign
- Professional CRM-style dashboard with KPI cards, Chart.js charts, and quick actions
- CRM section: 7 KPI cards (Accounts, Contacts, Leads, Deals, Activities, Won Revenue, Pipeline Value)
- Inventory section: 6 KPI cards (Quotes, SOs, POs, Invoices, Vendors, Revenue)
- Recent Activity: Leads, Invoices, Deals tables with colour-coded status badges

### v4.8.0 — Dual-Rail Sidebar Navigation
- Replaced single sidebar with 60px icon rail + 220px slide-out module panel
- Each module has its own dedicated panel with sub-menus and accordion groups
- Mobile-responsive with overlay close

### v4.7.0 — Inventory Sub-modules: Checkboxes + 3-dot Menus
- All 5 inventory listing pages (Price Books, Sales Orders, POs, Invoices, Vendors): checkboxes, row-click, 3-dot bulk menu
- All 5 inventory view pages: 3-dot action menu (Clone, Export PDF, Print Preview, Send Mail, Delete)
- 20 new routes

### v4.6.0 — Quotes: Checkboxes + Bulk Menu + View Actions
- Quotes listing: checkboxes, row-click, 3-dot bulk menu
- Quote view: Clone, Print Preview, Export to PDF, Send Mail, Delete
- Convert to Sales Order and Convert to Invoice

### v4.5.0 — Sales Order → Invoice Conversion
- `soConvertToInvoice()` copies all SO fields to a new Invoice with INV-XXXXXXXX number

### v4.4.x — PostgreSQL Migration + Inventory View Pages
- Migrated all 1,186 rows from MySQL to PostgreSQL 16.14
- Fixed all MySQL-specific migration calls (`->after()`, `MONTH()`, `YEAR()`)
- Inventory view pages: Price Books, Quotes, Sales Orders, POs, Invoices, Vendors
- All listing pages: row-click navigation + eye icon View button

### v4.3.0 — Quote Conversion (Sales Order + Invoice)
- `quoteConvertToSalesOrder()` and `quoteConvertToInvoice()` with full field mapping

### v4.2.0 — Inventory View Pages: Notes, Attachments, Email
- Notes with delete, Activities, Attachments tabs on all 6 inventory view pages
- Fixed SMTP field name mismatches across all 5 sendMail methods

### v4.1.0 — Inventory Module Foundation
- Price Books, Quotes, Sales Orders, Purchase Orders, Invoices, Vendors
- Full CRUD with line items, taxes, discounts, and address blocks

### v4.0.0 — Subscription Module Access, AI Tenant Creation, Payment Flow
- Plan-based module gating (`config/xenoraa.php` plan_modules mapping)
- Razorpay checkout page at registration
- AI-powered tenant content generation from business info (GPT-4o-mini)
- 3-step onboarding: register → payment → business info → AI content generation

### v3.8.0 — Point of Sale (POS) Module
- POS terminal with product grid, cart, and checkout
- Sessions, orders, reports, and settings
- 13 POS routes

### v3.x — Theme System, E-Commerce, CRM Foundation
- 8 site themes (Personal, Creative, Minimal, Corporate, ShopFront, Corpora, etc.)
- E-commerce module: products, categories, reviews, store config
- CRM foundation: Leads, Contacts, Accounts, Deals, Activities
- AI chatbot with GPT-4o-mini and 63 training entries

### v2.x — Xenoraa SaaS Launch
- Multi-tenant architecture with domain-aware routing
- Marketing site: Home, Features, Pricing, Showcase, Blog
- Super Admin dashboard
- Razorpay payment integration
- Xenoraa-branded onboarding flow

---

## License

Proprietary — All rights reserved. © 2025–2026 Xenoraa / Go Esscay Solutions.

---

<div align="center">
  <strong>Xenoraa — All-in-One Business Platform</strong><br>
  <a href="https://xenoraa.com">xenoraa.com</a> · <a href="mailto:support@xenoraa.com">support@xenoraa.com</a>
</div>
