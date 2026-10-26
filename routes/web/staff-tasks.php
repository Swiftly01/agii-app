<?php

use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\StaffTaskController;
use Illuminate\Support\Facades\Route;

// Staff work on their own tasks, the policy decides who may see or change each one
Route::middleware('auth')
    ->prefix('staff/tasks')
    ->name('staff.tasks.')
    ->controller(StaffTaskController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{task}', 'show')->whereNumber('task')->name('show');
                Route::get('/create', 'create')->name('create');
            Route::get('/{task}/edit', 'edit')->whereNumber('task')->name('edit');
        Route::put('/{task}', 'update')->whereNumber('task')->name('update');
        Route::delete('/{task}', 'destroy')->whereNumber('task')->name('destroy');
        Route::post('/', 'store')->name('store');
        Route::post('/{task}/start', 'start')->whereNumber('task')->name('start');
        Route::post('/{task}/complete', 'complete')->whereNumber('task')->name('complete');
        Route::patch('/{task}/progress', 'progress')->whereNumber('task')->name('progress');
    });

// Admins list and assign tasks
Route::middleware(['auth', 'admin'])
    ->prefix('admin/staff-tasks')
    ->name('admin.staff-tasks.')
    ->controller(AdminTaskController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{task}', 'show')->whereNumber('task')->name('show');
    });
