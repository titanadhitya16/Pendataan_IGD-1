<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscortDataController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes (Admin/Staff)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public User Authentication Routes
Route::get('/loginuser', [AuthController::class, 'showPublicLoginForm'])->name('loginuser');
Route::post('/loginuser/login', [AuthController::class, 'publicLogin'])->name('loginuser.login');
Route::post('/loginuser/register', [AuthController::class, 'publicRegister'])->name('loginuser.register');

// Form Routes (require public user authentication)
Route::middleware(['public.user'])->group(function () {
    Route::get('/form', [EscortDataController::class, 'index'])->name('form.index');
    Route::post('/form/submit', [EscortDataController::class, 'store'])->name('form.store');
});

// QR Code generation route
Route::get('/qr-code/form', [EscortDataController::class, 'generateFormQrCode'])->name('qr.form');

// QR Code demo/test page
Route::get('/qr-demo', function () {
    $qrUrl = route('qr.form');
    $formUrl = route('form.index');
    return view('qr-demo', compact('qrUrl', 'formUrl'));
})->name('qr.demo');

// Excel implementation test page
Route::get('/excel-test', function () {
    return view('excel-test');
})->name('excel.test');

// Session and submission tracking routes (public for form debugging)
Route::get('/submission/{submissionId}', [EscortDataController::class, 'getSubmissionDetails'])->name('submission.details');

// Protected Routes (require authentication - IGD Staff only)
Route::middleware(['auth', 'admin.user'])->group(function () {
    Route::get('/dashboard', [EscortDataController::class, 'dashboard'])->name('dashboard');
    
    // Escort status management
    Route::patch('/escorts/{escort}/status', [EscortDataController::class, 'updateStatus'])->name('escorts.update-status');
    
    // Data export/download routes
    Route::post('/dashboard/download/csv', [EscortDataController::class, 'downloadCsv'])->name('dashboard.download.csv');
    Route::post('/dashboard/download/excel', [EscortDataController::class, 'downloadExcel'])->name('dashboard.download.excel');
    
    // Admin utilities
    Route::post('/admin/clear-session-data', [EscortDataController::class, 'clearOldSessionData'])->name('admin.clear-session');
});

// CSRF Token endpoint for SPA/API clients
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

// Redirect root to dashboard (only for admin/staff, public users should use /form)
Route::get('/', function () {
    if (auth()->check()) {
        // Public users accessing root should be redirected to form
        if (auth()->user()->is_public_user) {
            return redirect()->route('form.index');
        }
        // Admin/staff users go to dashboard
        return redirect()->route('dashboard');
    }
    // Unauthenticated users go to admin login
    return redirect()->route('login');
})->name('home');