<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Mail;

// Public routes
Route::get('/', function() {
    $featuredEvents = \App\Models\Event::where('status', 'published')
        ->where('starts_at', '>', now())
        ->latest()
        ->take(3)
        ->get();
    return view('welcome', compact('featuredEvents'));
})->name('welcome');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Auth + email verified required
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customer bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::post('/bookings/pay', [BookingController::class, 'processPayment'])->name('bookings.pay');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // Organiser event management
    Route::middleware('role:organiser,admin')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        // Important: /events/create must come BEFORE /events/{event} to avoid 404 shadowing
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/events/{event}/bookings', [EventController::class, 'bookings'])->name('events.bookings');
        Route::get('/events/{event}/tickets', [EventController::class, 'manageTickets'])->name('events.tickets');
        Route::post('/events/{event}/tickets', [EventController::class, 'updateTickets'])->name('events.tickets.update');
        Route::post('/events/{event}/tickets/add', [EventController::class, 'addTicketType'])->name('events.tickets.add');
    });
    
    
    // Admin only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/revenue', [AdminController::class, 'revenue'])->name('admin.revenue');
        Route::patch('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('admin.events.approve');
        Route::patch('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('admin.events.reject');
    });
});

// Public event display (Placed at the end to avoid shadowing specific /events routes like /events/create)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/test-email', function () {

    Mail::raw('Test email from Laravel', function ($message) {
        $message->to('nheshan177@gmail.com')
                ->subject('Laravel SMTP Test');
    });

    return 'Email sent!';
});

Route::get('/fix-storage', function () {
    $link = public_path('storage');
    
    // Clear any existing folder or broken symlink
    if (file_exists($link) || is_link($link)) {
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // On Windows, directory symlinks must be deleted via rmdir
                if (is_dir($link)) {
                    rmdir($link);
                } else {
                    unlink($link);
                }
            } else {
                unlink($link);
            }
        } catch (\Exception $e) {
            // Fallback: attempt rename if locked
            try {
                rename($link, $link . '_bak_' . time());
            } catch (\Exception $ex) {}
        }
    }
    
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    
    return 'Storage link recreated successfully! Artisan output: <pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
});

Route::get('/profile-photo/{path}', function ($path) {
    $fullPath = 'profile-photos/' . $path;
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($fullPath)) {
        abort(404);
    }
    
    $file = \Illuminate\Support\Facades\Storage::disk('public')->get($fullPath);
    $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($fullPath);
    
    return response($file, 200)->header('Content-Type', $mimeType);
})->name('profile-photo.show');