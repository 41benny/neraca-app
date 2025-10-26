<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\CoaImportController;
use App\Http\Controllers\JournalImportController;
use App\Http\Controllers\JournalLineController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reports\ArApSummaryController;
use App\Http\Controllers\Reports\BalanceSheetController;
use App\Http\Controllers\Reports\IncomeStatementController;
use App\Http\Controllers\TemplateDownloadController;
use Illuminate\Support\Facades\App;
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

// Halaman UI untuk laporan (HTML)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('reports/balance-sheet/view', function () {
        $asOf = request()->date('as_of') ?? now();
        $service = App::make(\App\Services\FinancialReportService::class);
        $data = $service->balanceSheet($asOf, request('branch_code'));

        return view('reports.balance-sheet', ['data' => $data]);
    })->name('reports.balance-sheet.view');

    Route::get('reports/income-statement/view', function () {
        $start = request()->date('start_date') ?? now()->startOfMonth();
        $end = request()->date('end_date') ?? now();
        $filters = request()->only(['branch_code', 'invoice_id', 'project_id', 'vehicle_id']);
        $service = App::make(\App\Services\FinancialReportService::class);
        $data = $service->incomeStatement($start, $end, $filters);

        return view('reports.income-statement', ['data' => $data]);
    })->name('reports.income-statement.view');

    // AR/AP Summary (UI)
    Route::get('reports/ar-ap', ArApSummaryController::class)->name('reports.ar-ap');

    // Import COA + Saldo Awal
    Route::get('accounts/import', function () {
        return view('accounts.import');
    })->name('accounts.imports.create');

    Route::post('accounts/import', CoaImportController::class)
        ->name('accounts.imports.store');

    // Download templates (Excel only)
    Route::get('templates/coa.xlsx', [TemplateDownloadController::class, 'coaXlsx'])->name('templates.coa.xlsx');
    Route::get('templates/journal.xlsx', [TemplateDownloadController::class, 'journalXlsx'])->name('templates.journal.xlsx');

    // Master Akun manual
    Route::resource('accounts', AccountController::class)->except(['show']);

    // Jurnal Lines (lihat & edit)
    Route::get('journals', [JournalLineController::class, 'index'])->name('journals.index');
    Route::get('journals/{journalLine}/edit', [JournalLineController::class, 'edit'])->name('journals.edit');
    Route::put('journals/{journalLine}', [JournalLineController::class, 'update'])->name('journals.update');

    // Kas & Bank (manual entry -> auto journal)
    Route::get('cash-transactions/create', [CashTransactionController::class, 'create'])->name('cash.create');
    Route::post('cash-transactions', [CashTransactionController::class, 'store'])->name('cash.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
