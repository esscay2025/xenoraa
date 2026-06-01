<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Public\PortfolioController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\CalendarController;
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
// AUTHENTICATED USER PROFILE
// =============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
// REDIRECT AFTER LOGIN
// =============================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isStaff()) {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('home');
    }
})->middleware('auth')->name('dashboard');
