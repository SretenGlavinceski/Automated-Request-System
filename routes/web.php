<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketApprovalController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\NotificationRuleController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketStatusController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware('auth')
    ->name('dashboard');

Route::resource('service-items', ServiceItemController::class)
    ->except(['show'])
    ->middleware('auth');

Route::resource('tickets', TicketController::class)
    ->middleware('auth');

Route::post(
    '/tickets/{ticket}/approval',
    [TicketApprovalController::class, 'store']
)->middleware('auth')->name('tickets.approval.store');

Route::post(
    '/tickets/{ticket}/comments',
    [TicketCommentController::class, 'store']
)->middleware('auth')->name('tickets.comments.store');

Route::get(
    '/audit-logs',
    [AuditLogController::class, 'index']
)->middleware('auth')->name('audit-logs.index');

Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('users.index');

Route::get('/users/create', [UserController::class, 'create'])
    ->middleware('auth')
    ->name('users.create');

Route::post('/users', [UserController::class, 'store'])
    ->middleware('auth')
    ->name('users.store');

Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('auth')
    ->name('users.edit');

Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('users.update');

Route::post(
    '/notifications/{notificationId}/read',
    [NotificationController::class, 'markAsRead']
)->middleware('auth')->name('notifications.read');

Route::get(
    '/notification-logs',
    [NotificationLogController::class, 'index']
)
    ->middleware('auth')
    ->name('notification-logs.index');

Route::resource(
    'notification-rules',
    NotificationRuleController::class
)
    ->except(['show'])
    ->middleware('auth');

Route::post(
    '/tickets/{ticket}/attachments',
    [TicketAttachmentController::class, 'store']
)
    ->middleware('auth')
    ->name('tickets.attachments.store');

Route::get(
    '/tickets/{ticket}/attachments/{attachment}/download',
    [TicketAttachmentController::class, 'download']
)
    ->middleware('auth')
    ->name('tickets.attachments.download');

Route::put(
    '/tickets/{ticket}/status',
    [TicketStatusController::class, 'update']
)
    ->middleware('auth')
    ->name('tickets.status.update');
