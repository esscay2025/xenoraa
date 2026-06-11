<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\ThemeController as SuperAdminThemeController;
use App\Http\Controllers\Xenoraa\XenoraaController;
use App\Http\Controllers\Xenoraa\TenantProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Public\PortfolioController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\CalendarController;
use App\Http\Controllers\Public\UserDashboardController;
use App\Http\Controllers\Public\ChatController;
use App\Http\Controllers\Public\ForumController;
use App\Http\Controllers\Public\ShopController;
use App\Http\Controllers\Public\ChatbotController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Xenoraa\OnboardingController;
use App\Http\Controllers\Xenoraa\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Reserved usernames that cannot be used as tenant slugs
$reservedUsernames = '^(?!admin|staff|superadmin|api|auth|login|register|logout|dashboard|profile|chat|forum|calendar|shop|newsletter|chatbot|xenoraa|solutions|about|blog|jobs|privacy|terms|payment|onboarding|user).*$';

// =============================================
// ROOT ROUTE — Domain-aware
// xenoraa.com → Xenoraa marketing homepage
// gopi.blog or any custom domain → Portfolio
// =============================================

Route::get('/', function (\Illuminate\Http\Request $request) {
    $host = $request->getHost();
    $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
    if ($host === $mainDomain || $host === 'www.' . $mainDomain) {
        return app(\App\Http\Controllers\Xenoraa\XenoraaController::class)->home();
    }
    return app(\App\Http\Controllers\Public\PortfolioController::class)->home($request);
})->name('home');

// =============================================
// CUSTOM DOMAIN PUBLIC ROUTES
// (gopi.blog/about, gopi.blog/blog, etc.)
// These routes work on custom domains where no username prefix is needed
// =============================================
Route::get('/about', [PortfolioController::class, 'about'])->name('about');
Route::get('/contact', [PortfolioController::class, 'contact'])->name('contact');
Route::get('/services', [PortfolioController::class, 'services'])->name('services.page');
Route::get('/solutions', [PortfolioController::class, 'services'])->name('solutions.page');
Route::get('/practice-areas', [PortfolioController::class, 'services'])->name('practice-areas');
Route::get('/collaborations', [PortfolioController::class, 'services'])->name('collaborations');
Route::get('/appointments', [PortfolioController::class, 'services'])->name('appointments');
Route::get('/portfolio', [PortfolioController::class, 'portfolioPage'])->name('portfolio');
Route::get('/case-studies', [PortfolioController::class, 'portfolioPage'])->name('case-studies');
Route::get('/ventures', [PortfolioController::class, 'portfolioPage'])->name('ventures');
Route::get('/vision', [PortfolioController::class, 'portfolioPage'])->name('vision');
Route::get('/initiatives', [PortfolioController::class, 'portfolioPage'])->name('initiatives');
Route::prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/{topic}', [ForumController::class, 'show'])->name('show');
    Route::middleware('auth')->group(function () {
        Route::post('/{topic}/reply', [ForumController::class, 'reply'])->name('reply');
        Route::delete('/reply/{reply}', [ForumController::class, 'deleteReply'])->name('reply.delete');
        Route::post('/', [ForumController::class, 'createTopic'])->name('create');
    });
});
Route::get('/page/{slug}', [PortfolioController::class, 'customPage'])->name('page');
Route::get('/blog', [PortfolioController::class, 'blog'])->name('blog');
Route::get('/blog/category/{slug}', [PortfolioController::class, 'blogCategory'])->name('blog.category');
Route::get('/blog/{slug}', [PortfolioController::class, 'blogShow'])->name('blog.show');
Route::post('/blog/{slug}/comment', [PortfolioController::class, 'submitComment'])->name('blog.comment');
Route::get('/jobs', [PortfolioController::class, 'jobs'])->name('jobs');
Route::get('/jobs/{slug}', [PortfolioController::class, 'jobShow'])->name('jobs.show');
Route::post('/jobs/{slug}/apply', [PortfolioController::class, 'applyJob'])->name('jobs.apply');

// =============================================
// SOLUTIONS ROUTES
// =============================================
require __DIR__.'/solutions.php';

// =============================================
// SHOP / E-COMMERCE ROUTES (Public)
// =============================================
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.product');

// =============================================
// AI CHATBOT ROUTES (Public)
// =============================================
Route::get('/chatbot/init', [ChatbotController::class, 'init'])->name('chatbot.init');
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/contact', [ChatbotController::class, 'saveContact'])->name('chatbot.contact');
Route::post('/chatbot/save-contact', [ChatbotController::class, 'saveContact'])->name('chatbot.save-contact'); // alias for widget compatibility
Route::post('/chatbot/message', [ChatbotController::class, 'chat'])->name('chatbot.message'); // alias — widget JS calls /chatbot/message

// =============================================
// AUTHENTICATION ROUTES (Breeze)
// =============================================
require __DIR__.'/auth.php';

// =============================================
// CALENDAR & NOTES MODULE (accessible to all)
// =============================================
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/calendar/events', [CalendarController::class, 'storeEvent'])->name('calendar.events.store');
Route::put('/calendar/events/{event}', [CalendarController::class, 'updateEvent'])->name('calendar.events.update');
Route::delete('/calendar/events/{event}', [CalendarController::class, 'destroyEvent'])->name('calendar.events.destroy');
Route::post('/calendar/notes', [CalendarController::class, 'storeNote'])->name('calendar.notes.store');
Route::put('/calendar/notes/{note}', [CalendarController::class, 'updateNote'])->name('calendar.notes.update');
Route::delete('/calendar/notes/{note}', [CalendarController::class, 'destroyNote'])->name('calendar.notes.destroy');
Route::patch('/calendar/notes/{note}/pin', [CalendarController::class, 'togglePin'])->name('calendar.notes.pin');

// =============================================
// NEWSLETTER ROUTES
// =============================================
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// =============================================
// SOCIAL OAUTH ROUTES
// =============================================
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// =============================================
// USER DASHBOARD (regular logged-in users)
// =============================================
Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// =============================================
// AUTHENTICATED USER PROFILE
// =============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================
// CHAT MODULE (authenticated users)
// =============================================
Route::middleware('auth')->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/messages', [ChatController::class, 'sendMessage'])->name('send');
    Route::delete('/messages/{message}', [ChatController::class, 'deleteMessage'])->name('delete');
});

// =============================================
// FORUM MODULE (public read, auth to post)
// =============================================
Route::prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/{topic}', [ForumController::class, 'show'])->name('show');
    Route::middleware('auth')->group(function () {
        Route::post('/{topic}/reply', [ForumController::class, 'reply'])->name('reply');
        Route::delete('/reply/{reply}', [ForumController::class, 'deleteReply'])->name('reply.delete');
        Route::post('/', [ForumController::class, 'createTopic'])->name('create');
    });
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::patch('/{topic}/pin', [ForumController::class, 'togglePin'])->name('pin');
        Route::patch('/{topic}/lock', [ForumController::class, 'toggleLock'])->name('lock');
        Route::delete('/{topic}', [ForumController::class, 'deleteTopic'])->name('delete');
    });
});

// =============================================
// ADMIN ROUTES (Admin only)
// =============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'subscribed'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    // Role Management
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::put('roles/{role}/system', [RoleController::class, 'updateSystemRole'])->name('roles.update-system');

    // Blog Management
    Route::resource('blog', BlogController::class);
    Route::get('/blog-comments', [BlogController::class, 'comments'])->name('blog.comments');
    Route::patch('/blog-comments/{comment}/toggle', [BlogController::class, 'toggleComment'])->name('blog.comments.toggle');
    Route::delete('/blog-comments/{comment}', [BlogController::class, 'destroyComment'])->name('blog.comments.destroy');

    // Job Portal Management
    Route::resource('jobs', JobController::class);
    Route::get('/jobs/{job}/applications', [JobController::class, 'applications'])->name('jobs.applications');
    Route::patch('/applications/{application}/status', [JobController::class, 'updateApplicationStatus'])->name('applications.status');

    // Expense Manager
    Route::resource('expenses', ExpenseController::class);
    Route::patch('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::patch('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');

    // Site Settings (legacy)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings/social/{social}', [SettingsController::class, 'updateSocial'])->name('settings.social.update');
    Route::post('/settings/social', [SettingsController::class, 'storeSocial'])->name('settings.social.store');
    Route::get('/settings/social/{social}/destroy', [SettingsController::class, 'destroySocial'])->name('settings.social.destroy');
    Route::post('/settings/change-password', [SettingsController::class, 'changePassword'])->name('settings.change-password');

    // ─── Site Builder Module ──────────────────────────────────────
    Route::prefix('site')->name('site.')->group(function () {
        // Hub
        Route::get('/', [SiteController::class, 'index'])->name('index');

        // Theme Store
        Route::get('/themes', [SiteController::class, 'themes'])->name('themes');
        Route::post('/themes/activate', [SiteController::class, 'activateTheme'])->name('themes.activate');

        // Page Manager
        Route::get('/pages', [SiteController::class, 'pages'])->name('pages');
        Route::get('/pages/create', [SiteController::class, 'createPage'])->name('pages.create');
        Route::post('/pages', [SiteController::class, 'storePage'])->name('pages.store');
        Route::get('/pages/{page}/edit', [SiteController::class, 'editPage'])->name('pages.edit');
        Route::put('/pages/{page}', [SiteController::class, 'updatePage'])->name('pages.update');
        Route::post('/pages/{page}/sections', [SiteController::class, 'saveSections'])->name('pages.sections.save');
        Route::delete('/pages/{page}', [SiteController::class, 'destroyPage'])->name('pages.destroy');
        Route::post('/pages/reset', [SiteController::class, 'resetPages'])->name('pages.reset');

        // Menu Builder
        Route::get('/menu', [SiteController::class, 'menu'])->name('menu');
        Route::post('/menu', [SiteController::class, 'saveMenu'])->name('menu.save');

        // Branding
        Route::get('/branding', [SiteController::class, 'branding'])->name('branding');
        Route::post('/branding', [SiteController::class, 'saveBranding'])->name('branding.save');

        // Domain Configuration
        Route::get('/domain', [SiteController::class, 'domain'])->name('domain');
        Route::post('/domain', [SiteController::class, 'saveDomain'])->name('domain.save');

        // Dashboard Mode (light/dark) — AJAX
        Route::post('/mode', [SiteController::class, 'saveDashboardMode'])->name('mode');
    });

    // Community: Forum Admin
    Route::get('/forum', [\App\Http\Controllers\Admin\ForumAdminController::class, 'index'])->name('forum.index');
    Route::get('/forum/{topic}', [\App\Http\Controllers\Admin\ForumAdminController::class, 'show'])->name('forum.show');
    Route::patch('/forum/{topic}/pin', [\App\Http\Controllers\Admin\ForumAdminController::class, 'pin'])->name('forum.pin');
    Route::patch('/forum/{topic}/lock', [\App\Http\Controllers\Admin\ForumAdminController::class, 'lock'])->name('forum.lock');
    Route::delete('/forum/{topic}', [\App\Http\Controllers\Admin\ForumAdminController::class, 'destroy'])->name('forum.destroy');
    Route::delete('/forum/reply/{reply}', [\App\Http\Controllers\Admin\ForumAdminController::class, 'destroyReply'])->name('forum.reply.destroy');
    Route::patch('/forum/reply/{reply}/restore', [\App\Http\Controllers\Admin\ForumAdminController::class, 'approveReply'])->name('forum.reply.restore');

    // Community: Chat Admin
    Route::get('/chat', [\App\Http\Controllers\Admin\ChatAdminController::class, 'index'])->name('chat.index');
    Route::delete('/chat/{message}', [\App\Http\Controllers\Admin\ChatAdminController::class, 'destroy'])->name('chat.destroy');
    Route::patch('/chat/{message}/restore', [\App\Http\Controllers\Admin\ChatAdminController::class, 'restore'])->name('chat.restore');
    Route::post('/chat/clear-channel', [\App\Http\Controllers\Admin\ChatAdminController::class, 'clearChannel'])->name('chat.clear');

    // Community: Calendar Admin
    Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/events', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'storeEvent'])->name('calendar.events.store');
    Route::post('/calendar/notes', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'storeNote'])->name('calendar.notes.store');
    Route::delete('/calendar/events/{event}', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'destroy'])->name('calendar.destroy');
    Route::delete('/calendar/notes/{note}', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'destroyNote'])->name('calendar.note.destroy');

    // Newsletter Admin
    Route::get('/newsletter', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('/newsletter/export', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'export'])->name('newsletter.export');

    // ─── New Full CRM Module ──────────────────────────────────────────────
    Route::prefix('newcrm')->name('newcrm.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\NewCrmController::class, 'dashboard'])->name('dashboard');
        // Accounts
        Route::get('/accounts', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountsIndex'])->name('accounts');
        Route::get('/accounts/create', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountCreate'])->name('accounts.create');
        Route::post('/accounts', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountStore'])->name('accounts.store');
        Route::get('/accounts/{account}', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountShow'])->name('accounts.show');
        Route::get('/accounts/{account}/edit', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountEdit'])->name('accounts.edit');
        Route::put('/accounts/{account}', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountUpdate'])->name('accounts.update');
        Route::delete('/accounts/{account}', [\App\Http\Controllers\Admin\NewCrmController::class, 'accountDestroy'])->name('accounts.destroy');
        // Contacts
        Route::get('/contacts', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactsIndex'])->name('contacts');
        Route::get('/contacts/create', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactCreate'])->name('contacts.create');
        Route::post('/contacts', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactStore'])->name('contacts.store');
        Route::get('/contacts/{contact}/edit', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactEdit'])->name('contacts.edit');
        Route::put('/contacts/{contact}', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactUpdate'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\NewCrmController::class, 'contactDestroy'])->name('contacts.destroy');
        // Leads (unified)
        Route::get('/leads', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadsIndex'])->name('leads');
        Route::get('/leads/create', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadCreate'])->name('leads.create');
        Route::post('/leads', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadStore'])->name('leads.store');
        Route::get('/leads/{lead}', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadShow'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadEdit'])->name('leads.edit');
        Route::put('/leads/{lead}', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadUpdate'])->name('leads.update');
        Route::delete('/leads/{lead}', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadDestroy'])->name('leads.destroy');
        Route::post('/leads/{lead}/convert', [\App\Http\Controllers\Admin\NewCrmController::class, 'leadConvert'])->name('leads.convert');
        // Deals & Pipeline
        Route::get('/deals', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealsIndex'])->name('deals');
        Route::get('/deals/create', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealCreate'])->name('deals.create');
        Route::post('/deals', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealStore'])->name('deals.store');
        Route::get('/deals/{deal}/edit', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealEdit'])->name('deals.edit');
        Route::put('/deals/{deal}', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealUpdate'])->name('deals.update');
        Route::delete('/deals/{deal}', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealDestroy'])->name('deals.destroy');
        Route::patch('/deals/{deal}/stage', [\App\Http\Controllers\Admin\NewCrmController::class, 'dealUpdateStage'])->name('deals.stage');
        // Activities
        Route::get('/activities', [\App\Http\Controllers\Admin\NewCrmController::class, 'activitiesIndex'])->name('activities');
        Route::post('/activities', [\App\Http\Controllers\Admin\NewCrmController::class, 'activityStore'])->name('activities.store');
        Route::patch('/activities/{activity}/complete', [\App\Http\Controllers\Admin\NewCrmController::class, 'activityComplete'])->name('activities.complete');
        Route::delete('/activities/{activity}', [\App\Http\Controllers\Admin\NewCrmController::class, 'activityDestroy'])->name('activities.destroy');
    });

    // CRM Module (legacy AI Hub routes kept for backward compat)
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/leads', [\App\Http\Controllers\Admin\CrmController::class, 'leadsIndex'])->name('leads');
        Route::get('/leads/{lead}', [\App\Http\Controllers\Admin\CrmController::class, 'leadShow'])->name('lead.show');
        Route::patch('/leads/{lead}', [\App\Http\Controllers\Admin\CrmController::class, 'leadUpdate'])->name('lead.update');
        Route::patch('/leads/{lead}/status', [\App\Http\Controllers\Admin\CrmController::class, 'leadUpdate'])->name('lead.status');
        Route::delete('/leads/{lead}', [\App\Http\Controllers\Admin\CrmController::class, 'leadDestroy'])->name('lead.destroy');
        Route::post('/leads/{lead}/reply-email', [\App\Http\Controllers\Admin\CrmController::class, 'sendReplyEmail'])->name('lead.reply-email');
        Route::get('/conversations', [\App\Http\Controllers\Admin\CrmController::class, 'conversationsIndex'])->name('conversations');
        Route::get('/conversations/{conversation}', [\App\Http\Controllers\Admin\CrmController::class, 'conversationShow'])->name('conversation.show');
        Route::post('/conversations/{conversation}/reply', [\App\Http\Controllers\Admin\CrmController::class, 'conversationReply'])->name('conversation.reply');
        Route::delete('/conversations/{conversation}', [\App\Http\Controllers\Admin\CrmController::class, 'conversationDestroy'])->name('conversation.destroy');
        // AI Assistant Toggle
        Route::get('/ai-toggle', [\App\Http\Controllers\Admin\CrmController::class, 'aiToggle'])->name('ai.toggle');
        Route::post('/ai-toggle', [\App\Http\Controllers\Admin\CrmController::class, 'saveAiToggle'])->name('ai.toggle.save');

        Route::get('/training', [\App\Http\Controllers\Admin\CrmController::class, 'trainingIndex'])->name('training');
        Route::post('/training', [\App\Http\Controllers\Admin\CrmController::class, 'trainingStore'])->name('training.store');
        Route::put('/training/{training}', [\App\Http\Controllers\Admin\CrmController::class, 'trainingUpdate'])->name('training.update');
        Route::delete('/training/{training}', [\App\Http\Controllers\Admin\CrmController::class, 'trainingDestroy'])->name('training.destroy');
        Route::get('/requirements', [\App\Http\Controllers\Admin\CrmController::class, 'requirementsIndex'])->name('requirements');
        Route::patch('/requirements/{requirement}/scope-sent', [\App\Http\Controllers\Admin\CrmController::class, 'markScopeSent'])->name('requirements.scope-sent');
    });

    // ─── CRM2 Module (Complete CRM Rebuild) ─────────────────────────────────
    Route::prefix('crm2')->name('crm2.')->group(function () {
        $ctrl = \App\Http\Controllers\Admin\CrmModuleController::class;

        // Analysis & Reports
        Route::get('/analysis', [$ctrl, 'analysis'])->name('analysis');
        Route::get('/reports',  [$ctrl, 'reports'])->name('reports');

        // Sales sub-module routes
        Route::get('/sales/leads',                  [$ctrl, 'salesLeads'])->name('sales.leads');
        Route::get('/sales/leads/create',           [$ctrl, 'salesLeadsCreate'])->name('sales.leads.create');
        Route::get('/sales/leads/{id}',             [$ctrl, 'salesLeadsShow'])->name('sales.leads.show');
        Route::post('/sales/leads/{id}/convert',    [$ctrl, 'salesLeadsConvert'])->name('sales.leads.convert');
        Route::get('/sales/contacts',               [$ctrl, 'salesContacts'])->name('sales.contacts');
        Route::get('/sales/contacts/create',        [$ctrl, 'salesContactsCreate'])->name('sales.contacts.create');
        Route::post('/sales/contacts',               [$ctrl, 'salesContactsStore'])->name('sales.contacts.store');
        Route::get('/sales/contacts/{id}',           [$ctrl, 'salesContactsShow'])->name('sales.contacts.show');
        Route::patch('/sales/contacts/{id}',         [$ctrl, 'salesContactsUpdate'])->name('sales.contacts.update');
        Route::get('/sales/accounts',               [$ctrl, 'salesAccounts'])->name('sales.accounts');
        Route::get('/sales/accounts/create',        [$ctrl, 'salesAccountsCreate'])->name('sales.accounts.create');
        Route::post('/sales/accounts',               [$ctrl, 'salesAccountsStore'])->name('sales.accounts.store');
        Route::get('/sales/accounts/{id}',           [$ctrl, 'salesAccountsShow'])->name('sales.accounts.show');
        Route::patch('/sales/accounts/{id}',         [$ctrl, 'salesAccountsUpdate'])->name('sales.accounts.update');
        Route::get('/sales/deals',                  [$ctrl, 'salesDeals'])->name('sales.deals');
        Route::get('/sales/deals/create',           [$ctrl, 'salesDealsCreate'])->name('sales.deals.create');
        Route::post('/sales/deals',                  [$ctrl, 'salesDealsStore'])->name('sales.deals.store');
        Route::get('/sales/deals/{id}',              [$ctrl, 'salesDealsShow'])->name('sales.deals.show');
        Route::patch('/sales/deals/{id}',            [$ctrl, 'salesDealsUpdate'])->name('sales.deals.update');
        Route::get('/sales/forecasts',              [$ctrl, 'salesForecasts'])->name('sales.forecasts');
        Route::get('/sales/forecasts/create',       [$ctrl, 'salesForecastsCreate'])->name('sales.forecasts.create');
        Route::get('/sales/leads/{id}/edit',        [$ctrl, 'salesLeadsEdit'])->name('sales.leads.edit');
        Route::get('/sales/contacts/{id}/edit',     [$ctrl, 'salesContactsEdit'])->name('sales.contacts.edit');
        Route::get('/sales/accounts/{id}/edit',     [$ctrl, 'salesAccountsEdit'])->name('sales.accounts.edit');
        Route::delete('/sales/accounts/{id}',        [$ctrl, 'salesAccountsDestroy'])->name('sales.accounts.destroy');
        // Account sub-resource routes
        Route::post('/sales/accounts/{id}/notes',           [$ctrl, 'accountNotesStore'])->name('accounts.notes.store');
        Route::post('/sales/accounts/{id}/activities',      [$ctrl, 'accountActivitiesStore'])->name('accounts.activities.store');
        Route::post('/sales/accounts/{id}/assign',          [$ctrl, 'accountAssign'])->name('accounts.assign');
        // Account Emails
        Route::get('/sales/accounts/{id}/emails',                [$ctrl, 'accountEmailsList'])->name('accounts.emails.list');
        Route::post('/sales/accounts/{id}/emails',               [$ctrl, 'accountEmailsStore'])->name('accounts.emails.store');
        Route::get('/sales/accounts/{id}/emails/template',       [$ctrl, 'accountEmailsGetTemplate'])->name('accounts.emails.template');
        Route::patch('/sales/accounts/{id}/emails/{emailId}',    [$ctrl, 'accountEmailsUpdate'])->name('accounts.emails.update');
        Route::delete('/sales/accounts/{id}/emails/{emailId}',   [$ctrl, 'accountEmailsDestroy'])->name('accounts.emails.destroy');
        Route::get('/sales/deals/{id}/edit',        [$ctrl, 'salesDealsEdit'])->name('sales.deals.edit');
        Route::get('/sales/forecasts/{id}/edit',    [$ctrl, 'salesForecastsEdit'])->name('sales.forecasts.edit');
        Route::post('/sales',                       [$ctrl, 'salesStore'])->name('sales.store');
        Route::patch('/sales/{type}/{id}',          [$ctrl, 'salesUpdate'])->name('sales.update');
        Route::delete('/sales/{type}/{id}',         [$ctrl, 'salesDestroy'])->name('sales.destroy');
        // Legacy redirect
        Route::get('/sales',                        [$ctrl, 'salesLeads'])->name('sales');

        // Activities sub-module routes
        Route::get('/activities/tasks',             [$ctrl, 'activitiesTasks'])->name('activities.tasks');
        Route::get('/activities/tasks/create',      [$ctrl, 'activitiesTasksCreate'])->name('activities.tasks.create');
        Route::get('/activities/meetings',          [$ctrl, 'activitiesMeetings'])->name('activities.meetings');
        Route::get('/activities/meetings/create',   [$ctrl, 'activitiesMeetingsCreate'])->name('activities.meetings.create');
        Route::get('/activities/calls',             [$ctrl, 'activitiesCalls'])->name('activities.calls');
        Route::get('/activities/calls/create',      [$ctrl, 'activitiesCallsCreate'])->name('activities.calls.create');
        Route::get('/activities/tasks/{id}/edit',   [$ctrl, 'activitiesTasksEdit'])->name('activities.tasks.edit');
        Route::get('/activities/meetings/{id}/edit',[$ctrl, 'activitiesMeetingsEdit'])->name('activities.meetings.edit');
        Route::get('/activities/calls/{id}/edit',   [$ctrl, 'activitiesCallsEdit'])->name('activities.calls.edit');
        Route::post('/activity',                    [$ctrl, 'activityStore'])->name('activity.store');
        Route::patch('/activity/{id}',              [$ctrl, 'activityUpdate'])->name('activity.update');
        Route::patch('/activity/{id}/complete',     [$ctrl, 'activityComplete'])->name('activity.complete');
        Route::delete('/activity/{id}',             [$ctrl, 'activityDestroy'])->name('activity.destroy');
        // Legacy redirect
        Route::get('/activities',                   [$ctrl, 'activitiesTasks'])->name('activities');

        // Inventory sub-module routes
        Route::get('/inventory/price-books',        [$ctrl, 'inventoryPriceBooks'])->name('inventory.price-books');
        Route::get('/inventory/price-books/create', [$ctrl, 'inventoryPriceBooksCreate'])->name('inventory.price-books.create');
        Route::get('/inventory/quotes',             [$ctrl, 'inventoryQuotes'])->name('inventory.quotes');
        Route::get('/inventory/quotes/create',      [$ctrl, 'inventoryQuotesCreate'])->name('inventory.quotes.create');
        Route::get('/inventory/sales-orders',       [$ctrl, 'inventorySalesOrders'])->name('inventory.sales-orders');
        Route::get('/inventory/sales-orders/create',[$ctrl, 'inventorySalesOrdersCreate'])->name('inventory.sales-orders.create');
        Route::get('/inventory/purchase-orders',    [$ctrl, 'inventoryPurchaseOrders'])->name('inventory.purchase-orders');
        Route::get('/inventory/purchase-orders/create',[$ctrl,'inventoryPurchaseOrdersCreate'])->name('inventory.purchase-orders.create');
        Route::get('/inventory/invoices',           [$ctrl, 'inventoryInvoices'])->name('inventory.invoices');
        Route::get('/inventory/invoices/create',    [$ctrl, 'inventoryInvoicesCreate'])->name('inventory.invoices.create');
        Route::get('/inventory/vendors',            [$ctrl, 'inventoryVendors'])->name('inventory.vendors');
        Route::get('/inventory/vendors/create',     [$ctrl, 'inventoryVendorsCreate'])->name('inventory.vendors.create');
        Route::get('/inventory/price-books/{id}/edit',[$ctrl,'inventoryPriceBooksEdit'])->name('inventory.price-books.edit');
        Route::get('/inventory/quotes/{id}/edit',   [$ctrl, 'inventoryQuotesEdit'])->name('inventory.quotes.edit');
        Route::get('/inventory/sales-orders/{id}/edit',[$ctrl,'inventorySalesOrdersEdit'])->name('inventory.sales-orders.edit');
        Route::get('/inventory/purchase-orders/{id}/edit',[$ctrl,'inventoryPurchaseOrdersEdit'])->name('inventory.purchase-orders.edit');
        Route::get('/inventory/invoices/{id}/edit', [$ctrl, 'inventoryInvoicesEdit'])->name('inventory.invoices.edit');
        Route::get('/inventory/vendors/{id}/edit',  [$ctrl, 'inventoryVendorsEdit'])->name('inventory.vendors.edit');
        Route::post('/inventory',                   [$ctrl, 'inventoryStore'])->name('inventory.store');
        Route::patch('/inventory/{type}/{id}',      [$ctrl, 'inventoryUpdate'])->name('inventory.update');
        // Products sub-module
        Route::get('/inventory/products',              [$ctrl, 'inventoryProducts'])->name('inventory.products');
        Route::get('/inventory/products/create',       [$ctrl, 'inventoryProductsCreate'])->name('inventory.products.create');
        Route::post('/inventory/products',             [$ctrl, 'inventoryProductsStore'])->name('inventory.products.store');
        Route::get('/inventory/products/{id}',         [$ctrl, 'inventoryProductsShow'])->name('inventory.products.show');
        Route::get('/inventory/products/{id}/edit',    [$ctrl, 'inventoryProductsEdit'])->name('inventory.products.edit');
        Route::patch('/inventory/products/{id}',       [$ctrl, 'inventoryProductsUpdate'])->name('inventory.products.update');
        Route::delete('/inventory/products/{id}',      [$ctrl, 'inventoryProductsDestroy'])->name('inventory.products.destroy');
        // Inventory show routes
        Route::get('/inventory/price-books/{id}',      [$ctrl, 'inventoryPriceBooksShow'])->name('inventory.price-books.show');
        Route::get('/inventory/quotes/{id}',           [$ctrl, 'inventoryQuotesShow'])->name('inventory.quotes.show');
        Route::get('/inventory/sales-orders/{id}',     [$ctrl, 'inventorySalesOrdersShow'])->name('inventory.sales-orders.show');
        Route::get('/inventory/purchase-orders/{id}',  [$ctrl, 'inventoryPurchaseOrdersShow'])->name('inventory.purchase-orders.show');
        Route::get('/inventory/invoices/{id}',         [$ctrl, 'inventoryInvoicesShow'])->name('inventory.invoices.show');
        Route::get('/inventory/vendors/{id}',          [$ctrl, 'inventoryVendorsShow'])->name('inventory.vendors.show');
        Route::delete('/inventory/{type}/{id}',     [$ctrl, 'inventoryDestroy'])->name('inventory.destroy');
        // Legacy redirect
        Route::get('/inventory',                    [$ctrl, 'inventoryPriceBooks'])->name('inventory');

        // Support sub-module routes
        Route::get('/support/cases',                [$ctrl, 'supportCases'])->name('support.cases');
        Route::get('/support/cases/create',         [$ctrl, 'supportCasesCreate'])->name('support.cases.create');
        Route::get('/support/solutions',            [$ctrl, 'supportSolutions'])->name('support.solutions');
        Route::get('/support/solutions/create',     [$ctrl, 'supportSolutionsCreate'])->name('support.solutions.create');
        Route::get('/support/cases/{id}/edit',      [$ctrl, 'supportCasesEdit'])->name('support.cases.edit');
        Route::get('/support/solutions/{id}/edit',  [$ctrl, 'supportSolutionsEdit'])->name('support.solutions.edit');
        Route::post('/support',                     [$ctrl, 'supportStore'])->name('support.store');
        Route::patch('/support/{type}/{id}',        [$ctrl, 'supportUpdate'])->name('support.update');
        Route::delete('/support/{type}/{id}',       [$ctrl, 'supportDestroy'])->name('support.destroy');
        // Legacy redirect
        Route::get('/support',                      [$ctrl, 'supportCases'])->name('support');

        // Services sub-module routes
        Route::get('/services/catalog',             [$ctrl, 'servicesCatalog'])->name('services.catalog');
        Route::get('/services/catalog/create',      [$ctrl, 'servicesCatalogCreate'])->name('services.catalog.create');
        Route::get('/services/bookings',            [$ctrl, 'servicesBookings'])->name('services.bookings');
        Route::get('/services/bookings/create',     [$ctrl, 'servicesBookingsCreate'])->name('services.bookings.create');
        Route::get('/services/catalog/{id}/edit',   [$ctrl, 'servicesCatalogEdit'])->name('services.catalog.edit');
        Route::get('/services/bookings/{id}/edit',  [$ctrl, 'servicesBookingsEdit'])->name('services.bookings.edit');
        Route::post('/services',                    [$ctrl, 'servicesStore'])->name('services.store');
        Route::patch('/services/{type}/{id}',       [$ctrl, 'servicesUpdate'])->name('services.update');
        Route::delete('/services/{type}/{id}',      [$ctrl, 'servicesDestroy'])->name('services.destroy');
        // Legacy redirect
        Route::get('/services',                     [$ctrl, 'servicesCatalog'])->name('services');

        // Projects sub-module routes
        Route::get('/projects/list',                [$ctrl, 'projectsList'])->name('projects.list');
        Route::get('/projects/list/create',         [$ctrl, 'projectsListCreate'])->name('projects.list.create');
        Route::get('/projects/tasks',               [$ctrl, 'projectsTasks'])->name('projects.tasks');
        Route::get('/projects/tasks/create',        [$ctrl, 'projectsTasksCreate'])->name('projects.tasks.create');
        Route::get('/projects/list/{id}/edit',      [$ctrl, 'projectsListEdit'])->name('projects.list.edit');
        Route::get('/projects/tasks/{id}/edit',     [$ctrl, 'projectsTasksEdit'])->name('projects.tasks.edit');
        Route::post('/projects',                    [$ctrl, 'projectsStore'])->name('projects.store');
        Route::patch('/projects/{type}/{id}',       [$ctrl, 'projectsUpdate'])->name('projects.update');
        Route::patch('/projects/task/{id}/status',  [$ctrl, 'projectTaskStatus'])->name('projects.task.status');
        Route::delete('/projects/{type}/{id}',      [$ctrl, 'projectsDestroy'])->name('projects.destroy');
        // Legacy redirect
        Route::get('/projects',                     [$ctrl, 'projectsList'])->name('projects');

        // ── Integrations sub-module routes ──────────────────────────────────
        Route::get('/integrations/mail-config',       [$ctrl, 'integrationMailConfig'])->name('integrations.mail-config');
        Route::post('/integrations/mail-config',      [$ctrl, 'integrationMailConfigSave'])->name('integrations.mail-config.save');
        Route::post('/integrations/mail-config/test', [$ctrl, 'integrationMailConfigTest'])->name('integrations.mail-config.test');

        // ── Settings sub-module routes ───────────────────────────────────────
        Route::get('/settings/mail-templates',                    [$ctrl, 'settingsMailTemplates'])->name('settings.mail-templates');
        Route::get('/settings/mail-templates/create',             [$ctrl, 'settingsMailTemplatesCreate'])->name('settings.mail-templates.create');
        Route::post('/settings/mail-templates',                   [$ctrl, 'settingsMailTemplatesStore'])->name('settings.mail-templates.store');
        Route::get('/settings/mail-templates/seed',               [$ctrl, 'settingsMailTemplatesSeedDefaults'])->name('settings.mail-templates.seed');
        Route::get('/settings/mail-templates/{id}',               [$ctrl, 'settingsMailTemplatesShow'])->name('settings.mail-templates.show');
        Route::get('/settings/mail-templates/{id}/edit',          [$ctrl, 'settingsMailTemplatesEdit'])->name('settings.mail-templates.edit');
        Route::patch('/settings/mail-templates/{id}',             [$ctrl, 'settingsMailTemplatesUpdate'])->name('settings.mail-templates.update');
        Route::delete('/settings/mail-templates/{id}',            [$ctrl, 'settingsMailTemplatesDestroy'])->name('settings.mail-templates.destroy');
        Route::get('/settings/mail-templates/{id}/preview',       [$ctrl, 'settingsMailTemplatesPreview'])->name('settings.mail-templates.preview');
    });

    // ─── CoreModules ──────────────────────────────────────────────────────

    // Projects (Portfolio)
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);

    // Testimonials
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
    Route::patch('testimonials/{testimonial}/approve', [\App\Http\Controllers\Admin\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::patch('testimonials/{testimonial}/reject', [\App\Http\Controllers\Admin\TestimonialController::class, 'reject'])->name('testimonials.reject');


    // Business Card
    Route::get('business-card', [\App\Http\Controllers\Admin\BusinessCardController::class, 'index'])->name('business-card.index');
    Route::post('business-card', [\App\Http\Controllers\Admin\BusinessCardController::class, 'store'])->name('business-card.store');
    Route::get('business-card/vcard', [\App\Http\Controllers\Admin\BusinessCardController::class, 'vcard'])->name('business-card.vcard');

    // Documents
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);

    // Media Gallery
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class);


    // Profile Enhancements
    Route::get('profile-enhanced', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'index'])->name('profile-enhanced.index');
    Route::post('profile/skills', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'storeSkill'])->name('profile.skills.store');
    Route::delete('profile/skills/{skill}', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'destroySkill'])->name('profile.skills.destroy');
    Route::post('profile/education', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'storeEducation'])->name('profile.education.store');
    Route::delete('profile/education/{education}', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'destroyEducation'])->name('profile.education.destroy');
    Route::post('profile/certifications', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'storeCertification'])->name('profile.certifications.store');
    Route::delete('profile/certifications/{certification}', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'destroyCertification'])->name('profile.certifications.destroy');
    Route::post('profile/languages', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'storeLanguage'])->name('profile.languages.store');
    Route::delete('profile/languages/{language}', [\App\Http\Controllers\Admin\ProfileEnhancementController::class, 'destroyLanguage'])->name('profile.languages.destroy');

    // E-commerce Admin
    Route::prefix('ecommerce')->name('ecommerce.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EcommerceController::class, 'dashboard'])->name('dashboard');
        Route::get('/categories', [\App\Http\Controllers\Admin\EcommerceController::class, 'categoriesIndex'])->name('categories');
        Route::post('/categories', [\App\Http\Controllers\Admin\EcommerceController::class, 'categoryStore'])->name('categories.store');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\EcommerceController::class, 'categoryUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\EcommerceController::class, 'categoryDestroy'])->name('categories.destroy');
        Route::get('/products', [\App\Http\Controllers\Admin\EcommerceController::class, 'productsIndex'])->name('products');
        Route::get('/products/create', [\App\Http\Controllers\Admin\EcommerceController::class, 'productCreate'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\Admin\EcommerceController::class, 'productStore'])->name('products.store');
        Route::get('/products/export', [\App\Http\Controllers\Admin\EcommerceController::class, 'productExport'])->name('products.export');
        Route::get('/products/template', [\App\Http\Controllers\Admin\EcommerceController::class, 'productTemplate'])->name('products.template');
        Route::post('/products/import', [\App\Http\Controllers\Admin\EcommerceController::class, 'productImport'])->name('products.import');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\EcommerceController::class, 'productEdit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\EcommerceController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\EcommerceController::class, 'productDestroy'])->name('products.destroy');
        Route::patch('/products/{product}/toggle', [\App\Http\Controllers\Admin\EcommerceController::class, 'productToggleFeatured'])->name('products.toggle');
        Route::get('/reviews', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewsIndex'])->name('reviews');
        Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewApprove'])->name('review.approve');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewDestroy'])->name('review.destroy');
        // ── Integrations ──────────────────────────────────────────────────────
        Route::get('/integrations/mail-config',        [\App\Http\Controllers\Admin\EcommerceController::class, 'mailConfigIndex'])->name('integrations.mail-config');
        Route::post('/integrations/mail-config',       [\App\Http\Controllers\Admin\EcommerceController::class, 'mailConfigSave'])->name('integrations.mail-config.save');
        Route::post('/integrations/mail-config/test',  [\App\Http\Controllers\Admin\EcommerceController::class, 'mailConfigTest'])->name('integrations.mail-config.test');

        // ── Settings — Mail Templates ─────────────────────────────────────────
        Route::get('/settings/mail-templates',                    [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplatesIndex'])->name('settings.mail-templates');
        Route::get('/settings/mail-templates/create',             [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateCreate'])->name('settings.mail-templates.create');
        Route::post('/settings/mail-templates',                   [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateStore'])->name('settings.mail-templates.store');
        Route::post('/settings/mail-templates/load-defaults',     [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateLoadDefaults'])->name('settings.mail-templates.load-defaults');
        Route::get('/settings/mail-templates/{id}',               [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateShow'])->name('settings.mail-templates.show');
        Route::get('/settings/mail-templates/{id}/edit',          [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateEdit'])->name('settings.mail-templates.edit');
        Route::put('/settings/mail-templates/{id}',               [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateUpdate'])->name('settings.mail-templates.update');
        Route::delete('/settings/mail-templates/{id}',            [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateDestroy'])->name('settings.mail-templates.destroy');
        Route::get('/settings/mail-templates/{id}/preview',       [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplatePreview'])->name('settings.mail-templates.preview');
        Route::post('/settings/mail-templates/{id}/toggle',       [\App\Http\Controllers\Admin\EcommerceController::class, 'mailTemplateToggle'])->name('settings.mail-templates.toggle');

    });

    // ─── POS Module ───────────────────────────────────────────────────────────
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PosController::class, 'terminal'])->name('terminal');
        Route::post('/session/open', [\App\Http\Controllers\Admin\PosController::class, 'openSession'])->name('open-session');
        Route::post('/session/{session}/close', [\App\Http\Controllers\Admin\PosController::class, 'closeSession'])->name('close-session');
        Route::get('/products/search', [\App\Http\Controllers\Admin\PosController::class, 'searchProducts'])->name('search-products');
        Route::post('/order', [\App\Http\Controllers\Admin\PosController::class, 'placeOrder'])->name('place-order');
        Route::get('/order/{order}', [\App\Http\Controllers\Admin\PosController::class, 'getOrder'])->name('get-order');
        Route::post('/order/{order}/void', [\App\Http\Controllers\Admin\PosController::class, 'voidOrder'])->name('void-order');
        Route::get('/orders', [\App\Http\Controllers\Admin\PosController::class, 'orders'])->name('orders');
        Route::get('/sessions', [\App\Http\Controllers\Admin\PosController::class, 'sessions'])->name('sessions');
        Route::get('/sessions/{session}', [\App\Http\Controllers\Admin\PosController::class, 'sessionDetail'])->name('session-detail');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\PosController::class, 'dashboard'])->name('dashboard');
        Route::get('/settings', [\App\Http\Controllers\Admin\PosController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\PosController::class, 'saveSettings'])->name('settings.save');
    });
});

// =============================================
// STAFF ROUTES (Staff + Admin)
// =============================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    // Staff dashboard redirects to the admin panel (which already gates modules by staff role permissions)
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
    Route::resource('expenses', ExpenseController::class);
});

// =============================================
// XENORAA MARKETING SITE ROUTES
// =============================================
Route::prefix('')->name('xenoraa.')->group(function () {
    Route::get('/xenoraa', [XenoraaController::class, 'home'])->name('home');
    Route::get('/xenoraa/features', [XenoraaController::class, 'features'])->name('features');
    Route::get('/xenoraa/pricing', [XenoraaController::class, 'pricing'])->name('pricing');
    Route::get('/xenoraa/showcase', [XenoraaController::class, 'showcase'])->name('showcase');
    Route::get('/xenoraa/blog', [XenoraaController::class, 'blog'])->name('blog');
    Route::get('/xenoraa/get-started', [XenoraaController::class, 'getStarted'])->name('get-started');
});

// =============================================
// LEGAL PAGES (Privacy Policy & Terms)
// =============================================
Route::get('/privacy', function () {
    return view('xenoraa.privacy');
})->name('legal.privacy');

Route::get('/terms', function () {
    return view('xenoraa.terms');
})->name('legal.terms');

// =============================================
// PAYMENT ROUTES (Razorpay)
// =============================================
Route::get('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout')->middleware('auth');
Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.create-order');
Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

// =============================================
// SUPER ADMIN ROUTES
// =============================================
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [SuperAdminController::class, 'analytics'])->name('analytics');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [SuperAdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/impersonate', [SuperAdminController::class, 'impersonateUser'])->name('users.impersonate');
    Route::get('/exit-impersonation', [SuperAdminController::class, 'exitImpersonation'])->name('exit-impersonation');
    Route::patch('/users/{id}/toggle-status', [SuperAdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/subscriptions', [SuperAdminController::class, 'subscriptions'])->name('subscriptions');
    // Plan Modules Management
    Route::get('/plan-modules', [SuperAdminController::class, 'planModules'])->name('plan-modules');
    Route::post('/plan-modules', [SuperAdminController::class, 'savePlanModules'])->name('plan-modules.save');
    Route::get('/revenue', [SuperAdminController::class, 'revenue'])->name('revenue');
    Route::get('/domains', [SuperAdminController::class, 'domains'])->name('domains');
    Route::patch('/domains/{id}', [SuperAdminController::class, 'updateDomain'])->name('domains.update');
    Route::get('/blog', [SuperAdminController::class, 'blog'])->name('blog');
    Route::get('/showcase', [SuperAdminController::class, 'showcase'])->name('showcase');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/emails', [SuperAdminController::class, 'emails'])->name('emails');
    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
    // SEO Manager
    Route::get('/seo', [SuperAdminController::class, 'seo'])->name('seo');
    Route::post('/seo', [SuperAdminController::class, 'updateSeo'])->name('seo.update');
    // Theme Store
    Route::get('/themes', [SuperAdminThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/create', [SuperAdminThemeController::class, 'create'])->name('themes.create');
    Route::post('/themes', [SuperAdminThemeController::class, 'store'])->name('themes.store');
    Route::get('/themes/{theme}/edit', [SuperAdminThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{theme}', [SuperAdminThemeController::class, 'update'])->name('themes.update');
    Route::delete('/themes/{theme}', [SuperAdminThemeController::class, 'destroy'])->name('themes.destroy');
    Route::patch('/themes/{theme}/toggle', [SuperAdminThemeController::class, 'toggleActive'])->name('themes.toggle');
    Route::get('/themes/{theme}/preview', [SuperAdminThemeController::class, 'preview'])->name('themes.preview');

    // ---- ADMINISTRATION: Customers ----
    Route::get('/customers', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/{id}/assign-subscription', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'assignSubscription'])->name('customers.assign-subscription');
    Route::patch('/customers/{id}/toggle-status', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('/customers/{id}', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{id}/impersonate', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'impersonate'])->name('customers.impersonate');
    Route::get('/exit-impersonation', [\App\Http\Controllers\SuperAdmin\CustomerController::class, 'exitImpersonation'])->name('exit-impersonation');

    // ---- ADMINISTRATION: Agents ----
    Route::get('/agents', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'create'])->name('agents.create');
    Route::post('/agents', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{id}', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'show'])->name('agents.show');
    Route::get('/agents/{id}/edit', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{id}', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'update'])->name('agents.update');
    Route::post('/agents/{id}/allot', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'allot'])->name('agents.allot');
    Route::post('/agents/{id}/pay-commission', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'payCommission'])->name('agents.pay-commission');

    // ---- ADMINISTRATION: Staff ----
    Route::get('/staff', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/staff/roles', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'rolesIndex'])->name('staff.roles');
    Route::put('/staff/roles/{id}', [\App\Http\Controllers\SuperAdmin\StaffController::class, 'updateRolePermissions'])->name('staff.roles.update');

    // ---- TRAINING HUB ----
    Route::prefix('training-hub')->name('training-hub.')->group(function () {
        Route::get('/training', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'training'])->name('training');
        Route::post('/training', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'trainingStore'])->name('training.store');
        Route::put('/training/{id}', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'trainingUpdate'])->name('training.update');
        Route::delete('/training/{id}', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'trainingDestroy'])->name('training.destroy');
        Route::get('/conversations', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'conversations'])->name('conversations');
        Route::get('/conversations/{sessionId}', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'conversationDetail'])->name('conversations.detail');
        Route::delete('/conversations/{sessionId}', [\App\Http\Controllers\SuperAdmin\TrainingHubController::class, 'conversationDestroy'])->name('conversations.destroy');
    });
});

// ---- AGENT PORTAL ----
Route::prefix('agent')->name('agent.')->middleware(['auth', 'sa.role:agent'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentDashboard'])->name('dashboard');
    Route::get('/create-customer', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentCreateCustomer'])->name('create-customer');
    Route::post('/create-customer', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentStoreCustomer'])->name('store-customer');
    Route::get('/my-customers', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentMyCustomers'])->name('my-customers');
    Route::get('/quota', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentQuota'])->name('quota');
    Route::get('/commissions', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentCommissions'])->name('commissions');
    Route::get('/payouts', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentPayouts'])->name('payouts');
    Route::get('/profile', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentProfile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\SuperAdmin\AgentController::class, 'agentUpdateProfile'])->name('profile.update');
});

// =============================================
// USERNAME AVAILABILITY CHECK (public API)
// =============================================
Route::get('/api/check-username', [OnboardingController::class, 'checkUsername'])->name('api.check-username');
Route::get('/xenoraa/check-username', [OnboardingController::class, 'checkUsername'])->name('xenoraa.check-username');

// =============================================
// ONBOARDING ROUTES (post-registration)
// =============================================
Route::middleware(['auth', 'subscribed'])->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
    Route::get('/business-info', [OnboardingController::class, 'businessInfo'])->name('business-info');
    Route::post('/business-info', [OnboardingController::class, 'saveBusinessInfo'])->name('business-info.save');
    Route::get('/profile', [OnboardingController::class, 'profile'])->name('profile');
    Route::post('/profile', [OnboardingController::class, 'saveProfile'])->name('profile.save');
    Route::get('/complete', [OnboardingController::class, 'complete'])->name('complete');



});

// =============================================
// REDIRECT AFTER LOGIN
// =============================================
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    $host = $request->getHost();
    $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');
    $isMainDomain = ($host === $mainDomain || $host === 'www.' . $mainDomain);

    if ($user->isSuperAdmin() && $isMainDomain) {
        return redirect()->route('superadmin.dashboard');
    }

    // SA Staff redirect
    if ($user->saRole && $user->saRole->name === 'staff' && $isMainDomain) {
        return redirect()->route('superadmin.dashboard');
    }

    // Agent redirect
    if ($user->saRole && $user->saRole->name === 'agent' && $isMainDomain) {
        return redirect()->route('agent.dashboard');
    }

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isStaff()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// =============================================
// MULTI-TENANT USER PROFILE ROUTES
// (xenoraa.com/{username} and custom domains)
// =============================================

// Tenant login page: xenoraa.com/priya/login
Route::get('/{username}/login', function (\Illuminate\Http\Request $request, string $username) {
    $tenant = \App\Models\User::where('username', $username)->firstOrFail();
    return view('auth.tenant-login', compact('tenant'));
})->name('tenant.login')
  ->where('username', $reservedUsernames);

// Tenant-specific public pages: xenoraa.com/priya/about, /priya/blog, /priya/jobs
Route::get('/{username}/about', [PortfolioController::class, 'about'])->name('tenant.about')
    ->where('username', $reservedUsernames);

Route::get('/{username}/contact', [PortfolioController::class, 'contact'])->name('tenant.contact')
    ->where('username', $reservedUsernames);

Route::get('/{username}/services', [PortfolioController::class, 'services'])->name('tenant.services')
    ->where('username', $reservedUsernames);

Route::get('/{username}/solutions', [PortfolioController::class, 'services'])->name('tenant.solutions')
    ->where('username', $reservedUsernames);

Route::get('/{username}/practice-areas', [PortfolioController::class, 'services'])->name('tenant.practice-areas')
    ->where('username', $reservedUsernames);

Route::get('/{username}/collaborations', [PortfolioController::class, 'services'])->name('tenant.collaborations')
    ->where('username', $reservedUsernames);

Route::get('/{username}/appointments', [PortfolioController::class, 'services'])->name('tenant.appointments')
    ->where('username', $reservedUsernames);

Route::get('/{username}/portfolio', [PortfolioController::class, 'portfolioPage'])->name('tenant.portfolio')
    ->where('username', $reservedUsernames);

Route::get('/{username}/case-studies', [PortfolioController::class, 'portfolioPage'])->name('tenant.case-studies')
    ->where('username', $reservedUsernames);

Route::get('/{username}/ventures', [PortfolioController::class, 'portfolioPage'])->name('tenant.ventures')
    ->where('username', $reservedUsernames);

Route::get('/{username}/vision', [PortfolioController::class, 'portfolioPage'])->name('tenant.vision')
    ->where('username', $reservedUsernames);

Route::get('/{username}/initiatives', [PortfolioController::class, 'portfolioPage'])->name('tenant.initiatives')
    ->where('username', $reservedUsernames);

Route::get('/{username}/blog', [PortfolioController::class, 'blog'])->name('tenant.blog')
    ->where('username', $reservedUsernames);

Route::get('/{username}/blog/category/{slug}', [PortfolioController::class, 'blogCategory'])->name('tenant.blog.category')
    ->where('username', $reservedUsernames);

Route::get('/{username}/blog/{slug}', [PortfolioController::class, 'blogShow'])->name('tenant.blog.show')
    ->where('username', $reservedUsernames);

Route::post('/{username}/blog/{slug}/comment', [PortfolioController::class, 'submitComment'])->name('tenant.blog.comment')
    ->where('username', $reservedUsernames);

Route::get('/{username}/jobs', [PortfolioController::class, 'jobs'])->name('tenant.jobs')
    ->where('username', $reservedUsernames);

Route::get('/{username}/jobs/{slug}', [PortfolioController::class, 'jobShow'])->name('tenant.jobs.show')
    ->where('username', $reservedUsernames);

Route::post('/{username}/jobs/{slug}/apply', [PortfolioController::class, 'applyJob'])->name('tenant.jobs.apply')
    ->where('username', $reservedUsernames);

Route::get('/{username}/shop', [ShopController::class, 'tenantIndex'])->name('tenant.shop')
    ->where('username', $reservedUsernames);

// Tenant custom pages: xenoraa.com/priya/page/my-page-slug
Route::get('/{username}/page/{slug}', [PortfolioController::class, 'customPage'])->name('tenant.page')
    ->where('username', $reservedUsernames);

// Tenant homepage: xenoraa.com/priya
Route::get('/{username}', [PortfolioController::class, 'home'])->name('tenant.profile')
    ->where('username', $reservedUsernames);
