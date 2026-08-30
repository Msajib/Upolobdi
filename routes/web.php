<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public Portal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Member Portal & Role Control Panel (Auth protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('password.update');

    // Payment Operations
    Route::post('/payment/submit', [DashboardController::class, 'submitPayment'])->name('payment.submit');
    Route::post('/payment/direct', [DashboardController::class, 'createDirectPayment'])->name('payment.direct');
    Route::post('/payment/review/{id}', [DashboardController::class, 'reviewPayment'])->name('payment.review');

    // Printable & Downloadable Ledger Export (with pagination param)
    Route::get('/ledger/export', [DashboardController::class, 'exportLedger'])->name('ledger.export');
    Route::get('/payment/receipt/{id}', [DashboardController::class, 'viewReceipt'])->name('payment.receipt');

    // Due Exemption (Cashier max 6 mo, Superadmin max 12 mo)
    Route::post('/member/exemption/{userId}', [DashboardController::class, 'grantExemption'])->name('member.exemption');

    // Superadmin Member Management
    Route::post('/member/add', [DashboardController::class, 'addMember'])->name('member.add');
    Route::post('/member/update/{userId}', [DashboardController::class, 'updateMember'])->name('member.update');

    // Projects Management (President & VP)
    Route::post('/project/create', [DashboardController::class, 'createProject'])->name('project.create');
    Route::post('/project/update/{id}', [DashboardController::class, 'updateProject'])->name('project.update');
    Route::post('/project/delete/{id}', [DashboardController::class, 'deleteProject'])->name('project.delete');

    // Events Management
    Route::post('/event/create', [DashboardController::class, 'createEvent'])->name('event.create');
    Route::post('/event/update/{id}', [DashboardController::class, 'updateEvent'])->name('event.update');
    Route::post('/event/delete/{id}', [DashboardController::class, 'deleteEvent'])->name('event.delete');

    // Rules Management (Super Admin & Admin)
    Route::post('/rule/create', [DashboardController::class, 'createRule'])->name('rule.create');
    Route::post('/rule/update/{id}', [DashboardController::class, 'updateRule'])->name('rule.update');
    Route::post('/rule/delete/{id}', [DashboardController::class, 'deleteRule'])->name('rule.delete');

    // Announcements / Royal Decrees Management (President, Cashier, VP)
    Route::post('/announcement/create', [DashboardController::class, 'createAnnouncement'])->name('announcement.create');
    Route::post('/announcement/update/{id}', [DashboardController::class, 'updateAnnouncement'])->name('announcement.update');
    Route::post('/announcement/delete/{id}', [DashboardController::class, 'deleteAnnouncement'])->name('announcement.delete');

    // Settings & Site Customization (President only)
    Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/logo', [DashboardController::class, 'updateLogo'])->name('settings.logo');
    Route::post('/settings/roles', [DashboardController::class, 'updateMemberRoles'])->name('settings.roles');
});

// Statamic CP (reserved, credentials in README)
// Access at: /cp  - login: president@upolobdi.org / password123
