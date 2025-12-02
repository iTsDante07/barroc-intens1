<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Inkoop\ProductController as InkoopProductController;
use App\Http\Controllers\Inkoop\PurchaseOrderController;
use App\Http\Controllers\Inkoop\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {
    return view('auth.custom-login');
})->name('login');



// Profiel routes
Route::middleware(['auth'])->group(function () {
    // Profiel pagina tonen
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.edit');

    // Profiel bijwerken (naam en email)
    Route::put('/profile', function (Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    })->name('profile.update');

    // Wachtwoord wijzigen
    Route::put('/password', function (Request $request) {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('profile.edit')->with('status', 'password-updated');
    })->name('password.update');

    // Account verwijderen
    Route::delete('/profile', function (Request $request) {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Je account is succesvol verwijderd.');
    })->name('profile.destroy');

    // Email verificatie opnieuw sturen
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public product routes (voor klanten)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer routes
    Route::resource('customers', CustomerController::class);
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
    Route::get('/quotes/create-for-customer/{customer}', [QuoteController::class, 'createForCustomer'])->name('quotes.create.for.customer');
    Route::post('/quotes/store-for-customer/{customer}', [QuoteController::class, 'storeForCustomer'])->name('quotes.store.for.customer');

    // Invoice routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/create-from-quote/{quote}', [InvoiceController::class, 'createFromQuote'])->name('invoices.create.from.quote');
    Route::post('/invoices/store-from-quote/{quote}', [InvoiceController::class, 'storeFromQuote'])->name('invoices.store.from.quote');
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark.paid');
    Route::get('/invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download.pdf');

    // Maintenance routes
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/start', [MaintenanceController::class, 'start'])->name('maintenance.start');
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{maintenance}/cancel', [MaintenanceController::class, 'cancel'])->name('maintenance.cancel');
    Route::post('/maintenance/{maintenance}/technician-notes', [MaintenanceController::class, 'addTechnicianNotes'])->name('maintenance.technician-notes');
    Route::get('/customers/{customer}/maintenance/create', [MaintenanceController::class, 'createForCustomer'])->name('maintenance.create-for-customer');

    // Product management routes (alleen voor admins/managers)
    Route::middleware(['can:manage-products'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('/products/{product}/image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    });

    // Admin routes

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // User management actions
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update.role');
});

        // Inkoop Routes
     Route::prefix('inkoop')->name('inkoop.')->group(function () {
            // Producten beheer
            Route::get('/products', [InkoopProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [InkoopProductController::class, 'create'])->name('products.create');
            Route::post('/products', [InkoopProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [InkoopProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [InkoopProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [InkoopProductController::class, 'destroy'])->name('products.destroy');


            // Voorraad beheer
            Route::post('/products/{product}/update-stock', [InkoopProductController::class, 'updateStock'])->name('products.update-stock');
            Route::get('/low-stock', [InkoopProductController::class, 'lowStock'])->name('products.low-stock');

            // Bestellingen
            Route::get('/orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('/orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
            Route::post('/orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
            Route::get('/orders/{order}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
            Route::post('/orders/{order}/approve', [PurchaseOrderController::class, 'processApproval'])->name('purchase-orders.process-approval');

            // Meldingen
            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        });


// Settings routes

// Settings routes

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/notifications', 'settings.notifications')->name('notifications.edit');

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

// Debug route
Route::get('/check-table', function() {
    $columns = Schema::getColumnListing('maintenances');
    dd($columns);
});

require __DIR__.'/auth.php';
