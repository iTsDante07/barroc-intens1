<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\InvoiceController;


Route::get('/login', function () {
    return view('auth.custom-login');
})->name('login');

Route::resource('maintenance', MaintenanceController::class);
Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
Route::get('/customers/bkr-check', [CustomerController::class, 'bkrCheck'])->name('customers.bkr-check');
Route::post('/customers/quick-bkr-check', [CustomerController::class, 'quickBkrCheck'])->name('customers.quick-bkr-check');
Route::post('/customers/{customer}/check-bkr', [CustomerController::class, 'checkBkr'])->name('customers.check-bkr');
// Quote routes
Route::resource('quotes', QuoteController::class);
Route::post('/quotes/{quote}/send', [QuoteController::class, 'send'])->name('quotes.send');
Route::post('/quotes/{quote}/accept', [QuoteController::class, 'accept'])->name('quotes.accept');
Route::post('/quotes/{quote}/reject', [QuoteController::class, 'reject'])->name('quotes.reject');
Route::get('/quotes/{quote}/download-pdf', [QuoteController::class, 'downloadPdf'])->name('quotes.download.pdf');
Route::post('/quotes/{quote}/duplicate', [QuoteController::class, 'duplicate'])->name('quotes.duplicate');
// Snelle acties routes
Route::get('/quotes/create-for-customer/{customer}', [QuoteController::class, 'createForCustomer'])->name('quotes.create.for.customer');
Route::get('/maintenance/create-for-customer/{customer}', [MaintenanceController::class, 'createForCustomer'])->name('maintenance.create.for.customer');
// Invoice routes
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create-from-quote/{quote}', [InvoiceController::class, 'createFromQuote'])->name('invoices.create.from.quote');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark.paid');
Route::get('/invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download.pdf');
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
Route::get('/invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download.pdf');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Sales routes
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('quotes', QuoteController::class);
    Route::post('/customers/{customer}/check-bkr', [CustomerController::class, 'checkBkr'])->name('customers.check-bkr');

    // Finance routes
    Route::resource('invoices', InvoiceController::class);

    // Maintenance routes
<<<<<<< Updated upstream
    Route::get('/maintenance', function () {
        return view('maintenance.index');
    })->name('maintenance.index');
=======
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    // Maintenance Routes
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/start', [MaintenanceController::class, 'start'])->name('maintenance.start');
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');
>>>>>>> Stashed changes

    // Admin routes
    Route::get('/users', function () {
        $users = \App\Models\User::with('department')->get();
        return view('users.index', compact('users'));
    })->name('users.index');
});
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
