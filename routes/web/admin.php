<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminVendorController;

// In routes/web.php
Route::prefix('admin')->middleware('auth')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Vendors Management
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('admin.vendors.index');
    Route::get('/vendors/{id}', [AdminVendorController::class, 'show'])->name('admin.vendors.show');
    Route::get('/vendors/{id}/edit', [AdminVendorController::class, 'edit'])->name('admin.vendors.edit');
    Route::put('/vendors/{id}', [AdminVendorController::class, 'update'])->name('admin.vendors.update');
    Route::delete('/vendors/{id}', [AdminVendorController::class, 'destroy'])->name('admin.vendors.destroy');

    // Products Management
    Route::get('/products', [AdminController::class, 'productsForApproval'])->name('admin.products.index');
    Route::get('/products/pending', [AdminController::class, 'productsForApproval'])->name('admin.products.pending');
    Route::post('/products/{id}/approve', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
    Route::post('/products/{id}/toggle-featured', [AdminController::class, 'toggleFeatured'])->name('admin.products.toggle-featured');
    Route::post('/products/bulk-approve', [AdminController::class, 'bulkApproveProducts'])->name('admin.products.bulk-approve');

    Route::get('/getpendingproduct', [AdminController::class, 'getPendingProducts'])->name('admin.products.pendinglist');

    // Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
     Route::get('/products/{slug}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');


    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Customers Management
    Route::get('/customers', [AdminController::class, 'showCustomers'])->name('admin.customers.index');

    // Users Management
    // Route::get('/users', [AdminController::class, 'showUsers'])->name('admin.users.index');
    // Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    // Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    // Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    // Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    // Route::post('/users/{id}/assign-role', [AdminController::class, 'assignRole'])->name('admin.users.assign-role');

    // Transactions
    Route::get('/transactions', [AdminController::class, 'showTransactions'])->name('admin.transactions.index');

    // Tasks Management
    Route::get('/tasks', [AdminController::class, 'showTasks'])->name('admin.tasks.index');
    Route::get('/tasks/create', [AdminController::class, 'createTask'])->name('admin.tasks.create');
    Route::post('/tasks', [AdminController::class, 'storeTask'])->name('admin.tasks.store');
    
    Route::get('/tasks/{task}/edit', [AdminController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/tasks/{task}', [AdminController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/tasks/{task}', [AdminController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::get('/tasks/{task}', [AdminController::class, 'show'])->name('admin.tasks.show');
    
    Route::post('/admin/tasks/store-multiple', [AdminController::class, 'storeMultiple'])->name('admin.tasks.storeMultiple');
   
    
   
    Route::post('/tasks/{id}/status', [AdminController::class, 'updateTaskStatus'])->name('admin.tasks.update-status');

    // Admin Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');



    // Users
    Route::get('/users', [AdminController::class, 'showUsers'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/users/{id}/show', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/assign-role', [AdminController::class, 'assignRole'])->name('admin.users.assign-role');
    Route::post('/users/{id}/verify-email', [AdminController::class, 'verifyEmail'])->name('admin.users.verify-email');

    // Bulk user actions
    Route::post('/users/bulk-assign-role', [AdminController::class, 'bulkAssignRole'])->name('admin.users.bulk-assign-role');
    Route::post('/users/bulk-verify-email', [AdminController::class, 'bulkVerifyEmail'])->name('admin.users.bulk-verify-email');
    Route::post('/users/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admin.users.bulk-delete');
});

// Admin Login (outside auth middleware)
Route::get('/admin/login', [AdminController::class, 'index'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);


// Marketer routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('marketer')->group(function () {
        Route::get('/tasks', [AdminController::class, 'marketerTasks'])->name('marketer.tasks.index');
        Route::post('/tasks/{task}/status', [AdminController::class, 'updateTaskStatusMarketer'])->name('marketer.tasks.update-status');
        Route::get('/tasks/{task}', [AdminController::class, 'marketerTaskDetail'])->name('marketer.tasks.show');
        Route::post('/tasks/{task}/add-note', [AdminController::class, 'addNote'])->name('marketer.tasks.add-note');
         
        Route::post('/tasks/update-progress', [AdminController::class, 'updateProgress'])->name('marketer.tasks.update-progress'); 
         
    });
});




// Admin routes
// routes/web.php

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Vendor Advert Report
    Route::get('/vendor-advert-report', [ProductController::class, 'vendorAdvertReport'])
        ->name('vendor.advert.report');
    
    Route::get('/export-vendor-advert-report', [ProductController::class, 'exportVendorAdvertReport'])
        ->name('export.vendor.advert.report');
    
    Route::get('/export-vendor-report/{vendor}', [ProductController::class, 'exportVendorReport'])
        ->name('export.vendor.report');
});
