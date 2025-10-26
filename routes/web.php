<?php

use App\Http\Controllers\JournalImportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reports\BalanceSheetController;
use App\Http\Controllers\Reports\IncomeStatementController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Temporary debug route for login issues (remove after use)
Route::get('/debug-login', function () {
    $user = \App\Models\User::where('email', 'admin@neraca.com')->first();
    if (! $user) {
        return response()->json(['exists' => false]);
    }

    return response()->json([
        'exists' => true,
        'email' => $user->email,
        'check_password123' => Hash::check('password123', $user->password),
        'check_password' => Hash::check('password', $user->password),
    ]);
});

// Temporary: list users to verify emails in DB
Route::get('/debug-users', function () {
    $users = \App\Models\User::select('id', 'name', 'email', 'created_at')->orderBy('id')->get();

    return response()->json([
        'count' => $users->count(),
        'users' => $users,
    ]);
});

// Route untuk membuat user baru
Route::get('/create-user', [\App\Http\Controllers\CreateUserController::class, 'createUser']);

// Route untuk login manual
Route::get('/login-manual', [LoginController::class, 'showLoginForm'])->name('login.manual');
Route::post('/login-manual', [LoginController::class, 'login'])->name('login.manual.post');
Route::get('/create-admin', [LoginController::class, 'createAdmin']);

Route::post('journal-imports', JournalImportController::class)
    ->name('journal-imports.store');

Route::get('reports/balance-sheet', BalanceSheetController::class)
    ->name('reports.balance-sheet');

Route::get('reports/income-statement', IncomeStatementController::class)
    ->name('reports.income-statement');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
