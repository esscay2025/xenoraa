<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Xenoraa\XenoraaController;
use App\Http\Controllers\Xenoraa\TenantProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\UserController;
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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================================
// PUBLIC PORTFOLIO ROUTES (Visitor-facing)
// =============================================

Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/about', [PortfolioController::class, 'about'])->name('about');
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
    // Admin forum management
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::patch('/{topic}/pin', [ForumController::class, 'togglePin'])->name('pin');
        Route::patch('/{topic}/lock', [ForumController::class, 'toggleLock'])->name('lock');
        Route::delete('/{topic}', [ForumController::class, 'deleteTopic'])->name('delete');
    });
});

// =============================================
// ADMIN ROUTES (Admin only)
// =============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);

    // Blog Management
    Route::resource('blog', BlogController::class);
    Route::get('/blog-comments', [BlogController::class, 'comments'])->name('blog.comments');
    Route::patch('/blog-comments/{comment}/toggle', [BlogController::class, 'toggleComment'])->name('blog.comments.toggle');
    Route::delete('/blog-comments/{comment}', [BlogController::class, 'destroyComment'])->name('blog.comments.destroy');

    // Job Portal Management
    Route::resource('jobs', JobController::class);
    Route::get('/jobs/{job}/applications', [JobController::class, 'applications'])->name('jobs.applications');
    Route::patch('/applications/{application}/status', [JobController::class, 'updateApplicationStatus'])->name('applications.status');

    // Expense Manager (Admin sees all)
    Route::resource('expenses', ExpenseController::class);
    Route::patch('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::patch('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');

    // Site Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/social/{social}', [SettingsController::class, 'updateSocial'])->name('settings.social.update');

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
    Route::delete('/calendar/events/{event}', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'destroy'])->name('calendar.destroy');
    Route::delete('/calendar/notes/{note}', [\App\Http\Controllers\Admin\CalendarAdminController::class, 'destroyNote'])->name('calendar.note.destroy');

    // Newsletter Admin
    Route::get('/newsletter', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('/newsletter/export', [\App\Http\Controllers\Admin\NewsletterAdminController::class, 'export'])->name('newsletter.export');

    // CRM Module
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
        Route::get('/training', [\App\Http\Controllers\Admin\CrmController::class, 'trainingIndex'])->name('training');
        Route::post('/training', [\App\Http\Controllers\Admin\CrmController::class, 'storeTraining'])->name('training.store');
        Route::put('/training/{training}', [\App\Http\Controllers\Admin\CrmController::class, 'updateTraining'])->name('training.update');
        Route::delete('/training/{training}', [\App\Http\Controllers\Admin\CrmController::class, 'destroyTraining'])->name('training.destroy');
    });

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
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\EcommerceController::class, 'productEdit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\EcommerceController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\EcommerceController::class, 'productDestroy'])->name('products.destroy');
        Route::patch('/products/{product}/toggle', [\App\Http\Controllers\Admin\EcommerceController::class, 'productToggleFeatured'])->name('products.toggle');
        Route::get('/reviews', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewsIndex'])->name('reviews');
        Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewApprove'])->name('review.approve');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\EcommerceController::class, 'reviewDestroy'])->name('review.destroy');
    });
});

// =============================================
// STAFF ROUTES (Staff + Admin)
// =============================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:admin,staff'])->group(function () {

    // Staff Dashboard
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

    // Staff Expense Manager (own expenses only)
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
    Route::get('/revenue', [SuperAdminController::class, 'revenue'])->name('revenue');
    Route::get('/domains', [SuperAdminController::class, 'domains'])->name('domains');
    Route::patch('/domains/{id}', [SuperAdminController::class, 'updateDomain'])->name('domains.update');
    Route::get('/blog', [SuperAdminController::class, 'blog'])->name('blog');
    Route::get('/showcase', [SuperAdminController::class, 'showcase'])->name('showcase');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/emails', [SuperAdminController::class, 'emails'])->name('emails');
    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
});

// =============================================
// MULTI-TENANT USER PROFILE ROUTES
// (xenoraa.com/{username} and custom domains)
// =============================================
Route::get('/{username}', [TenantProfileController::class, 'profile'])->name('tenant.profile')
    ->where('username', '^(?!admin|staff|superadmin|api|auth|login|register|logout|dashboard|profile|chat|forum|calendar|shop|newsletter|chatbot|xenoraa|solutions|about|blog|jobs|staff).*$');
Route::get('/{username}/blog', [TenantProfileController::class, 'blog'])->name('tenant.blog')
    ->where('username', '^(?!admin|staff|superadmin|api|auth|login|register|logout|dashboard|profile|chat|forum|calendar|shop|newsletter|chatbot|xenoraa|solutions|about|blog|jobs|staff).*$');
Route::get('/{username}/blog/{slug}', [TenantProfileController::class, 'blogPost'])->name('tenant.blog.show')
    ->where('username', '^(?!admin|staff|superadmin|api|auth|login|register|logout|dashboard|profile|chat|forum|calendar|shop|newsletter|chatbot|xenoraa|solutions|about|blog|jobs|staff).*$');
Route::get('/{username}/shop', [TenantProfileController::class, 'shop'])->name('tenant.shop')
    ->where('username', '^(?!admin|staff|superadmin|api|auth|login|register|logout|dashboard|profile|chat|forum|calendar|shop|newsletter|chatbot|xenoraa|solutions|about|blog|jobs|staff).*$');

// =============================================
// REDIRECT AFTER LOGIN
// =============================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isStaff()) {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
})->middleware('auth')->name('dashboard');
