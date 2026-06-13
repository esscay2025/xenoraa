## v4.21.0 (2026-06-13): Xenoraa Documentation Hub
- New docs hub at /xenoraa/docs — 8 section cards with live search
- Individual section pages at /xenoraa/docs/{slug} with sidebar, breadcrumbs, prev/next
- 8 sections: Getting Started, Website Builder, E-Commerce, POS, CRM & Sales, Accounts & Finance, AI Hub, Billing
- Each section: step-by-step guides, tips, warnings, tables, troubleshooting FAQ
- Footer Documentation link updated to xenoraa.docs route
- XenoraaController: docs(), docsSection(), markdownToHtml() methods
- GitHub commit: 11611bf

- v4.18.0 (2026-06-13): Xenoraa Public Site — New 3-Tier Pricing + Content Update
  Pricing Page: complete rewrite with Solo App (₹499), Duo Bundle (₹999), All-Access (₹1999)
  Tier 1: 1 app of choice (Website OR E-Commerce OR POS OR CRM)
  Tier 2: 2-app combo (Website+E-Commerce, E-Commerce+POS, Website+CRM)
  Tier 3: All 4 apps + Inventory, Projects, Services, Support, AI Hub
  Monthly/Yearly toggle, 4 app pills, full comparison table, FAQ section
  Home Page: updated pricing cards + hero copy for 4-app model
  Features Page: 12 feature cards, updated heading
  GitHub commit: cd56a33

- v4.17.0 (2026-06-13): SSL for vignesh.solutions + Product Import Fix
  SSL Fix (vignesh.solutions):
  - Created /etc/nginx/sites-available/vignesh.solutions Nginx config (same Laravel app root as gopi.blog)
  - Issued Let's Encrypt SSL cert via certbot --nginx for vignesh.solutions + www.vignesh.solutions
  - Certificate valid until 2026-09-11, auto-renewal configured by certbot
  - vignesh.solutions now returns HTTP/2 200 with correct CN=vignesh.solutions
  - Root cause: no Nginx server block existed for vignesh.solutions; server was serving abirami.info cert

  Product Import Fix (Store Module):
  - Installed phpoffice/phpspreadsheet v5.8.0 via Composer (Excel .xlsx/.xls import now works)
  - Fixed products_sku_unique and products_slug_unique DB constraints from global to per-tenant
    (CREATE UNIQUE INDEX ON products(user_id, sku) and products(user_id, slug))
  - uniqueSlug() updated to check uniqueness per tenant (user_id parameter added)
  - productImport() now pre-checks for duplicate SKU per tenant before insert
  - Product::create wrapped in try/catch — each failed row shows specific error, no 500 crash
  - Commit: e5b3ec0

- v4.16.0 (2026-06-13): Deals Kanban View
  - Added List/Kanban view toggle to Deals landing page
  - Kanban board: 6 columns (Prospecting, Qualification, Proposal, Negotiation, Closed Won, Closed Lost)
  - Deal cards: name (linked), account, value, probability, expected close, owner
  - Column headers show deal count badge and total pipeline value
  - Drag-and-drop between columns using HTML5 Drag API (no external libraries)
  - AJAX PATCH to /admin/deals/{deal}/stage updates stage on drop (optimistic UI)
  - View preference saved in localStorage; List view paginated, Kanban unpaginated
  - salesDeals controller updated to pass allDeals for Kanban view
  - Commit: d3ad424


- v4.1.0 (2026-06-09): Theme Propagation Fix + Jose Industries Branding
  - layouts/app.blade.php: Dynamic color scheme from tenant site_settings (color_bg, color_primary, color_secondary, color_text); auto-detects light vs dark theme via luminance calculation
  - portfolio/shop.blade.php: Rewritten to use CSS variables (var(--accent), var(--bg-card), etc.) instead of hardcoded dark colors; shop title uses tenant site_name instead of hardcoded 'Gopi's Digital Shop'
  - portfolio/blog.blade.php: Updated to use CSS variables for theme consistency
  - portfolio/forum-index.blade.php: CSS override block injected after @endpush to map all hardcoded dark colors to CSS variables; forum title uses 
  - auth/tenant-login.blade.php: Fully rewritten to load tenant site_settings (logo, colors, tagline); shows tenant logo instead of avatar if available; uses tenant accent colors for buttons and links
  - Middleware: EnsurePaidSubscription added to gate /admin/* and /onboarding/* routes behind payment
  - app/Http/Kernel.php: 'subscribed' middleware alias registered
  - PaymentController.php: Fixed site_bootstrapped check to use SiteSetting::exists() instead of non-existent property
  - joseindustries (user id=15): Logos and favicon uploaded to /storage/logos/15/; site settings configured with green theme (color_bg=#f0fdf4, color_accent=#22c55e, color_primary=#16a34a)

- v4.2.0 (2026-06-09): Jose Industries Theme & E-Commerce Enhancement
  - layouts/app.blade.php: navbar uses white logo (logo_white_path) when isLightTheme=true (dark navbar on light bg)
  - layouts/app.blade.php: CSS :root block dynamically sets --bg-primary, --bg-secondary, --bg-card, --text-primary, --navbar-bg from tenant color_bg/color_primary/color_secondary/color_text site settings
  - resources/views/tenant/templates/ecommerce.blade.php: Fully rebuilt with CSS variables, hero slider, product categories, features section, newsletter
  - resources/views/portfolio/about.blade.php: Rebuilt with green theme CSS variables, rich Jose Industries content (story, certifications, manufacturing, team, factsheet)
  - about.blade.php: Fixed generic 'About Me' heading to use 'About {siteName}' when default heading detected
  - resources/views/admin/pos/terminal.blade.php: Fixed product image URLs (handles full http:// URLs from external sources)
  - resources/views/staff/dashboard.blade.php: Rebuilt to use tenant-specific URLs (custom domain aware)
  - app/Http/Controllers/Auth/AuthenticatedSessionController.php: Fixed staff redirect to always use custom domain when available
  - app/Http/Controllers/Admin/PosController.php: Fixed featured_image URL in AJAX search response
  - Jose Industries (user_id=15): Logos uploaded (/storage/logos/15/), site settings updated with real company data, 113 products across 17 categories imported from IndiaMart
- v4.3.0 (2026-06-09): About Page Tenant Theme Fix + POS Cart Layout Fix
  - resources/views/portfolio/about.blade.php: Fixed :root CSS block — replaced broken color-mix(in srgb, dark_bg, #000) calculations (which produced dark text on dark backgrounds for dark-themed tenants) with direct references to layout CSS variables (--bg-primary, --bg-card, --border, --text-primary, --text-secondary, --text-muted, --accent)
  - resources/views/layouts/app.blade.php: Added CSS variable aliases --color-bg, --color-accent, --color-text, --color-card, --color-border to :root block so about.blade.php and other pages can reference them as fallbacks
  - resources/views/admin/pos/terminal.blade.php: Fixed right panel layout — pos-right set to overflow:hidden height:100%; cart-items set to flex:1 1 0 min-height:0 overflow-y:auto (scrollable); checkout-panel set to flex-shrink:0 (pinned at bottom); checkout-panel div added to wrap all checkout sections (customer, discount, totals, payment buttons, charge button)
  - Verified: Jose Industries (green/light), Gopi K (indigo/dark) about pages show correct tenant-specific theme colors; POS cart scrollHeight(515)>clientHeight(393) confirming scroll works with 8 items

- v4.4.0 (2026-06-09): Collapsible Sidebar Navigation
  - Tenant admin layout (admin.blade.php): collapsible sidebar with 260px expanded / 64px collapsed
  - Collapsed state: icon-only display, labels/section headers/chevrons hidden
  - Hover flyout: floating submenu panel slides out to the right of sidebar when hovering a group button in collapsed mode
  - Collapse toggle button at the bottom of sidebar with chevron icon that rotates on collapse
  - State persisted in localStorage (key: xenoraa_sidebar_collapsed)
  - main-content margin transitions smoothly with sidebar width change
  - Super admin layout (superadmin.blade.php): same collapse/expand + flyout behavior
  - SA sidebar: icons added to all nav group headers for collapsed icon display
  - SA flyout: dark panel with purple active highlights matching SA theme
  - SA state persisted in localStorage (key: xenoraa_sa_sidebar_collapsed)
  - Both sidebars verified: expand/collapse/flyout all working in automated playwright tests

- v4.5.0 (2026-06-10): CRM Module Navigation Refactor + Sidebar Icon Fix
  - admin.blade.php: Added gap:0 to .sidebar.collapsed .sidebar-group-btn and .sidebar-link to fix icon alignment
  - admin.blade.php: Added .sidebar-sub-group-btn CSS class for nested CRM sub-groups
  - admin.blade.php: CRM sidebar restructured - each module (Sales, Activities, Inventory, Support, Services, Projects) is now a nested sub-group with its own flyout submenu
  - routes/web.php: Added dedicated routes per CRM sub-module (e.g. /admin/crm2/sales/leads, /admin/crm2/sales/leads/create, etc.)
  - CrmModuleController.php: Added 30+ new methods for each sub-module list and create page
  - New full-page views created (no more tabs, no more modals for creation):
    - crm2/sales/{leads,contacts,accounts,deals,forecasts}.blade.php (list pages)
    - crm2/sales/create-{lead,contact,account,deal,forecast}.blade.php (create pages)
    - crm2/activities/{tasks,meetings,calls}.blade.php + create-activity.blade.php
    - crm2/inventory/{price-books,quotes,sales-orders,purchase-orders,invoices,vendors}.blade.php
    - crm2/inventory/create-{price-book,quote,sales-order,purchase-order,invoice,vendor}.blade.php
    - crm2/support/{cases,solutions}.blade.php + create-{case,solution}.blade.php
    - crm2/services/{catalog,bookings}.blade.php + create-{service,booking}.blade.php
    - crm2/projects/{list,tasks}.blade.php + create-{project,task}.blade.php
  - Legacy routes kept for backward compatibility (redirect to first sub-module)

- v4.6.0 (2026-06-10): CRM Edit Popup Removal — Full-Page Edit Forms
  - All edit/view popup modals removed from every CRM sub-module list page
  - 20 new dedicated edit routes added (e.g. /admin/crm2/sales/leads/{id}/edit)
  - 20 new edit controller methods added to CrmModuleController.php
  - 20 new full-page edit views created (pre-filled with existing record data):
    - crm2/sales/edit-{lead,contact,account,deal,forecast}.blade.php
    - crm2/activities/edit-activity.blade.php (shared for tasks/meetings/calls)
    - crm2/inventory/edit-{price-book,quote,sales-order,purchase-order,invoice,vendor}.blade.php
    - crm2/support/edit-{case,solution}.blade.php
    - crm2/services/edit-{service,booking}.blade.php
    - crm2/projects/edit-{project,task}.blade.php
  - All list views updated: edit buttons now link to /{id}/edit pages instead of triggering JS popups
  - Inventory list views also gained edit buttons (previously only had delete)
  - inventoryUpdate() PATCH method added to handle all inventory sub-module updates

- v4.7.0 (2026-06-10): CRM 500 Error Fixes (create + edit pages)
  - Root cause 1: Blade syntax error - all edit views had \->id (backslash escape from Python generation) causing ParseError
  - Root cause 2: DB field mismatch - crm_leads has 'mobile' not 'phone', 'name' not 'first_name'/'last_name'
  - Root cause 3: crm_activities has 'subject'/'due_at' not 'title'/'due_date'
  - Root cause 4: inventoryUpdate/supportUpdate/servicesUpdate/projectsUpdate used wrong column names
  - Fix: All 20 edit views completely rewritten with correct DB column names
  - Fix: create-lead.blade.php: 'phone' -> 'mobile', removed 'company' field
  - Fix: create-activity.blade.php: 'title'/'due_date' -> 'subject'/'due_at'
  - Fix: CrmModuleController: salesStore/salesUpdate/activityStore/inventoryUpdate/supportUpdate/servicesUpdate/projectsUpdate all fixed
  - Fix: activities/tasks.blade.php, meetings.blade.php, calls.blade.php: title->subject, due_date->due_at
  - Fix: projects/tasks.blade.php: title->name
  - All 27 CRM pages verified returning HTTP 200

- v4.8.0 (2026-06-10): Enhanced Leads Module
  - Migration: 2026_06_10_073837_enhance_crm_leads_all_fields.php (35+ new columns)
  - New columns: lead_image, owner_id, lead_status, rating, salutation, first_name, last_name, title, company, industry, secondary_email, fax, website, twitter, linkedin, facebook, instagram, email_opt_out, country, flat_no, street, city, state, zip, annual_revenue, no_of_employees, budget, requirement, expected_purchase_date, decision_maker, competitor, interest_level, follow_up_date, campaign_source, campaign_name, referral_source, last_activity_date, converted_date, is_converted, description, internal_notes
  - create-lead.blade.php: 8-section grouped/responsive form (Profile, Personal, Contact, Address, Business, Qualification, Tracking, Notes)
  - view-lead.blade.php: Full detail view with Convert button, Convert confirmation popup, linked Activities (Task/Meeting/Call log + history)
  - Routes added: GET /sales/leads/{id} (show), POST /sales/leads/{id}/convert
  - Controller: salesLeadsCreate (staff dropdown), salesLeadsShow, salesLeadsConvert (creates Account+Contact+optional Deal)
  - CrmLead model: fillable updated with all new fields
  - Leads list: View (eye) button added, name is now a clickable link to view page
  - Demo data: 10 leads seeded (Arjun Mehta, Priya Sharma, Ravi Kumar, Fatima Al-Rashid, Lim Wei Jian, Kavitha Nair, Mohammed Al-Farsi, Siti Rahimah, Rajesh Patel, Ananya Krishnan)

- v4.9.0 (2026-06-10): Enhanced CRM Contact/Account/Deal modules
  - DB: Added ~35 new columns to crm_contacts (owner_id, mailing/other address, professional info, personal info)
  - DB: Added ~20 new columns to crm_accounts (owner_id, billing/shipping address, company info)
  - DB: Added ~10 new columns to crm_deals (owner_id, name, amount, closing_date, campaign_source, etc.)
  - Models: CrmContact, CrmAccount, CrmDeal fillable arrays updated; owner()/reportingTo() relationships added
  - Views: create-contact.blade.php - 8 grouped sections (Profile, Organization, Contact Info, Professional, Personal, Mailing, Other Address, Notes)
  - Views: view-contact.blade.php - full detail page with linked deals/leads/activities panel
  - Views: create-account.blade.php - 6 grouped sections (Profile, Company, Contact Info, Billing, Shipping, Notes)
  - Views: view-account.blade.php - full detail page with summary cards, linked contacts/deals
  - Views: create-deal.blade.php - grouped form with owner, account/contact dropdowns, stage, probability
  - Views: view-deal.blade.php - full detail with stage progress bar, linked activities
  - Routes: POST/GET store/show/update routes added for contacts, accounts, deals
  - Controller: salesContactsStore/Show/Update, salesAccountsStore/Show/Update, salesDealsStore/Show/Update added
  - List pages: accounts.blade.php and contacts.blade.php popup edit buttons replaced with view/edit page links
  - Lead fixes: CrmLead owner() relationship added; salesLeadsCreate passes staff list
- v4.9.2 (2026-06-10): CRM Sales module comprehensive fix
  - edit-lead.blade.php: Fully rewritten to match create-lead structure (8 sections, all fields, correct route)
  - edit-contact.blade.php: Rewritten to match create-contact structure (8 sections, staff/account dropdowns)
  - edit-account.blade.php: Rewritten to match create-account structure (6 sections, staff dropdown)
  - edit-deal.blade.php: Rewritten to match create-deal structure (stage, qualification, linked dropdowns)
  - view-contact/account/deal.blade.php: Fixed HTML escaping ({{ }} → {!! !!}) for fallback span values
  - salesLeadsEdit/salesContactsEdit/salesAccountsEdit/salesDealsEdit: Fixed to pass $staff, $accounts_list, $contacts_list
  - Demo data: 10 accounts, 10 contacts, 9 deals seeded for user_id=1 (Gopi K tenant)
  - crm_leads: Updated existing 10 demo leads with proper first_name/last_name split from name column

- v4.10.0 (2026-06-10): CRM Inventory Enhancement + New Products Sub-Module
  - DB: Added 30+ new columns to crm_price_books, crm_quotes, crm_sales_orders, crm_purchase_orders, crm_invoices, crm_vendors (billing/shipping address, contact_id, terms, etc.)
  - DB: Added balance_due column to crm_invoices
  - DB: Created crm_products table (owner_id, vendor_id, name, product_code, product_category, manufacturer, is_active, sales/support dates, unit_price, tax, commission_rate, qty_in_stock, reorder_level, description, image)
  - Model: App\Models\CrmProduct (with vendor() and owner() relationships)
  - Routes: 7 new product routes (admin.crm2.inventory.products.*) + 6 new show routes for all inventory sub-modules
  - Controller: Added inventoryPriceBooksShow, inventoryQuotesShow, inventorySalesOrdersShow, inventoryPurchaseOrdersShow, inventoryInvoicesShow, inventoryVendorsShow, inventoryProducts, inventoryProductsCreate, inventoryProductsStore, inventoryProductsShow, inventoryProductsEdit, inventoryProductsUpdate, inventoryProductsDestroy
  - Views: 39 new/updated blade files (3 per sub-module × 7 sub-modules = 21 create/view/edit + 7 list pages + 4 detail pages)
  - Sidebar: Products added to Inventory group in admin.blade.php
  - Demo data: Price Books (4), Vendors (3), Products (5), Quotes (3), Sales Orders (3), Purchase Orders (3), Invoices (3)
- v4.11.0 (2026-06-10): Comprehensive Account View Page Redesign
  - DB: Created crm_notes table (id, user_id, notable_type, notable_id, content, timestamps) — polymorphic notes for any CRM entity
  - DB: Created crm_account_products pivot table (account_id, product_id) — links products to accounts
  - Model: App\Models\CrmNote (user_id, notable_type, notable_id, content; belongs to User)
  - Model: App\Models\CrmAccount updated — added products(), notes(), activities(), quotes(), salesOrders(), invoices() relationships
  - View: resources/views/admin/crm2/sales/view-account.blade.php — fully redesigned with:
    - Frozen right-side section navigator (sticky, scroll-spy, 10 sections)
    - 10 sections: Account Information, Notes, Deals, Contacts, Open Activities, Closed Activities, Products, Quotes, Sales Orders, Invoices
    - Notes: inline add form + chronological list
    - Deals/Contacts: table list + Assign slider + New/Edit buttons
    - Open/Closed Activities: tabbed (Task/Meeting/Call) + Add Activity dropdown popup
    - Products: table list + Add Product slider with search
    - Quotes/Sales Orders/Invoices: table list + Assign slider + New button
    - All Assign sliders: searchable, AJAX-based toggle assign/unassign
    - Activity popup: Task/Meeting/Call type, subject, due date, status, description
    - Responsive layout (mobile: nav moves to top horizontal scroll)
  - Routes: POST /sales/accounts/{id}/notes (accounts.notes.store), POST /sales/accounts/{id}/activities (accounts.activities.store), POST /sales/accounts/{id}/assign (accounts.assign), DELETE /sales/accounts/{id} (sales.accounts.destroy)
  - Controller: Added accountNotesStore, accountActivitiesStore, accountAssign, salesAccountsDestroy methods; updated salesAccountsShow to pass 16 variables (notes, deals, contacts, openActivities, closedActivities, accountProducts, allProducts, quotes, salesOrders, invoices, allDeals, allContacts, allQuotes, allSalesOrders, allInvoices, leads)

- v4.11.1 (2026-06-10): CRM2 Theme Integration Fix
  - Root cause: crm2.css used hardcoded dark hex values (#0f172a, #1e293b, #263347) independent of dashboard theme
  - Fix: All --crm-* CSS variables now bridge to dashboard variables (--bg-primary, --bg-card, --bg-secondary, --bg-hover, --text-primary, --text-secondary, --text-muted, --border, --accent, --success, --danger, --warning, --info)
  - CRM2 list/landing pages now automatically follow user-selected theme (dark or light)
  - Added [data-theme="light"] overrides for crm2-input, crm2-select, crm2-textarea
  - products.blade.php: Replaced cf-page/cv-section list section with crm2-page/crm2-table (consistent with all other list pages)
  - Added new CSS classes: crm2-icon-btn.view, crm2-btn-success, acct-view-layout, acct-nav, crm2-slider, crm2-slider-overlay
  - Commit: 25cb965

- v4.11.3 (2026-06-10): CRM Inventory Bug Fixes
  - Bug 1 (500 errors on create): All 6 inventory create methods were missing required view variables.
    Fixed: inventoryPriceBooksCreate passes ; inventoryQuotesCreate passes ///;
    inventorySalesOrdersCreate passes ////;
    inventoryPurchaseOrdersCreate passes //;
    inventoryInvoicesCreate passes //; inventoryVendorsCreate passes .
  - Bug 2 (wrong redirect after update): Edit forms send singular type keys (quote, sales_order, invoice,
    purchase_order, price_book, vendor) but inventoryUpdate routeMap only had plural keys.
    Fixed: added both singular and plural keys to routeMap and switch cases in inventoryUpdate().
    Also expanded update field lists to include owner_id, contact_id etc.
  - Bug 3 (Products sidebar icon/alignment): Added sidebar-sub-sub-link class and fa-box-open icon.
  - Bonus: inventoryStore now redirects to correct list page after create (was using back()).
  - Commit: cfcf956

- v4.11.3 (2026-06-10): CRM Inventory Bug Fixes
  - Bug 1 (500 on create): All 6 create methods missing required view variables. Fixed: each now passes staff, accounts, contacts, deals, quotes, vendors as needed.
  - Bug 2 (wrong redirect after update): Edit forms send singular type keys but routeMap had plural keys only. Fixed: added singular keys to routeMap and switch cases in inventoryUpdate().
  - Bug 3 (Products sidebar): Added sidebar-sub-sub-link class and fa-box-open icon.
  - Bonus: inventoryStore now redirects to correct list page after create.
  - Commit: cfcf956

- v4.12.0 (2026-06-11): CRM Integrations + Settings Modules
  - New Module: Integrations > Mail Config
    - Per-tenant SMTP config: host, port, username, password (Crypt encrypted), encryption, from_address, from_name, reply_to
    - Send Test Email AJAX endpoint; verified_at + last_error tracking
    - Table: crm_mail_configs | Model: CrmMailConfig
    - Routes: admin.crm2.integrations.mail-config (GET/POST), admin.crm2.integrations.mail-config.test (POST)
  - New Module: Settings > Mail Templates
    - Full CRUD with 6 pre-built professional HTML templates: Invoice, Quote, Sales Order, Purchase Order, General, All-in-One
    - Features: logo upload (crm/mail-logos), colour pickers, font selector, live iframe preview, click-to-insert variables
    - is_default per type, is_active toggle, seed endpoint for first-time setup
    - Table: crm_mail_templates | Model: CrmMailTemplate
    - Routes: 9 routes (list, create, store, show, edit, update, destroy, seed, preview)
  - Sidebar: Integrations (fa-plug) + Settings (fa-cog) groups added inside CRM2 panel
  - Commit: e0cd10c

- v4.13.0 (2026-06-11): Email Section in Account View
  - New table: crm_account_emails (user_id, account_id, mail_template_id, status, to/cc/bcc, subject, body_html, scheduled_at, sent_at, error_message)
  - Model: App\Models\CrmAccountEmail (scopes: sent, drafts, scheduled)
  - 5 new routes under admin.crm2.accounts.emails.* (list, store, template, update, destroy)
  - salesAccountsShow updated to pass mailTemplates, mailConfig, sentEmails, draftEmails, scheduledEmails
  - Email section in view-account.blade.php:
    - Mail / Draft / Scheduled tabs with live counts
    - Scheduled tab has source dropdown (CRM sent / Contact associated)
    - Compose Mail button opens 520px right-side slider
    - Template picker (select + Load) auto-fills subject + body
    - Rich text editor (bold/italic/underline/lists/links/clear)
    - CC / BCC toggle fields
    - Schedule datetime picker
    - Send / Save Draft / Schedule via AJAX
    - Mail config warning banner links to Integrations > Mail Config
    - Email list: avatar, to, subject, preview, date, delete
    - Draft edit pre-fills compose slider
    - Email nav item in frozen right sidebar
  - Commit: 827838d

- v4.14.0 (2026-06-11): E-commerce Integrations & Settings Modules
  - New tables: ecom_mail_configs, ecom_mail_templates
  - Models: EcomMailConfig, EcomMailTemplate (app/Models/)
  - 13 routes under admin.ecommerce.integrations.* and admin.ecommerce.settings.*
  - Views: resources/views/admin/ecommerce/integrations/mail-config.blade.php
  - Views: resources/views/admin/ecommerce/settings/ (mail-templates, create, edit, view, preview)
  - 6 default e-commerce templates seeded for user_id=1 (gopi@outlook.in)
  - Sidebar: Integrations (plug icon) and Settings (cog icon) groups added after Point of Sale
  - Controller: 13 new methods in EcommerceController for mail config and templates

- v4.15.0 (2026-06-11): E-commerce Full Theme Integration
  - Created public/css/ecommerce.css with ec- CSS class system bridging to dashboard CSS variables
  - Rebuilt dashboard.blade.php, products.blade.php, categories.blade.php, reviews.blade.php, product-form.blade.php with ec- classes
  - All hardcoded dark hex values (#0f172a, #1e293b, #334155, #e2e8f0, #9ca3af) replaced with CSS variables
  - ecommerce.css linked in admin.blade.php with cache-busting filemtime() version
  - Integrations/Settings views already used var(--bg-card) etc. - no changes needed there
  - Products sub-group sidebar fix: Items grouped under Products collapsible at top of E-commerce menu
- v4.16.0 (2026-06-11): E-commerce Store Config Module
  - New table: ecom_store_configs (migration: 2026_06_11_400001_create_ecom_store_configs_table.php)
  - Model: app/Models/EcomStoreConfig.php (80+ fields, fillable, boolean/integer casts)
  - Controller: storeConfigIndex() and storeConfigSave() methods added to EcommerceController.php
  - Routes: GET/POST /admin/ecommerce/store-config added to routes/web.php
  - View: resources/views/admin/ecommerce/store-config.blade.php (991 lines, 8 tabs)
  - Sidebar: Store Config link (store icon) added to E-commerce panel in admin.blade.php
  - 8 tabs: General (store info, currency), Products (reviews, sorting, display), Shipping (methods, free shipping, flat rate, local pickup), Payments (COD, Razorpay, Stripe, PayPal, Bank Transfer, UPI), Accounts & Privacy (guest checkout, GDPR), Site Visibility (catalog, breadcrumbs, lightbox), Point of Sale (receipt, barcode, customer display), Advanced (coupons, stock, taxes, custom CSS/JS)
  - WooCommerce-style tab navigation with URL parameter (?tab=general etc.)
  - All fields use ec- CSS classes for dark/light mode adaptive styling
  - Deployed and verified working on production server at gopi.blog
