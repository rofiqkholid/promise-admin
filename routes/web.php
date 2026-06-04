<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/metrics', [AdminController::class, 'getRealMetrics'])->name('admin.dashboard.metrics');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/bulk-update-access', [AdminController::class, 'bulkUpdateAccess'])->name('admin.bulk-update');
    Route::post('/admin/update-scope-role', [AdminController::class, 'updateScopeRole'])->name('admin.update-scope-role');
    Route::post('/admin/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/admin/user-permissions/{userId}', [AdminController::class, 'getUserPermissions'])->name('admin.user-permissions');
    Route::post('/admin/update-user-permission', [AdminController::class, 'updateUserPermission'])->name('admin.update-user-permission');

    // Role & Permission Management Routes
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    Route::get('/admin/roles/{roleId}/permissions/{scopeId}', [RoleController::class, 'getRolePermissions'])->name('admin.roles.permissions');
    Route::post('/admin/roles/permissions', [RoleController::class, 'updatePermissions'])->name('admin.roles.permissions.update');
    Route::post('/admin/permissions', [RoleController::class, 'storePermission'])->name('admin.permissions.store');
    Route::delete('/admin/permissions/{id}', [RoleController::class, 'destroyPermission'])->name('admin.permissions.destroy');

    // Menu Management Routes
    Route::get('/admin/menus', [MenuController::class, 'index'])->name('admin.menus.index');
    Route::get('/admin/menus/ajax', [MenuController::class, 'ajaxList'])->name('admin.menus.ajax');
    Route::post('/admin/menus', [MenuController::class, 'store'])->name('admin.menus.store');
    Route::put('/admin/menus/{menu}', [MenuController::class, 'update'])->name('admin.menus.update');
    Route::delete('/admin/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');
    Route::post('/admin/scopes', [MenuController::class, 'storeScope'])->name('admin.scopes.store');
    Route::put('/admin/scopes/{id}', [MenuController::class, 'updateScope'])->name('admin.scopes.update');
    Route::delete('/admin/scopes/{id}', [MenuController::class, 'destroyScope'])->name('admin.scopes.destroy');

    // Master Configuration Routes
    Route::get('/admin/departments/ajax', [AdminController::class, 'departmentsAjax'])->name('admin.departments.ajax');
    Route::get('/admin/scopes/ajax', [AdminController::class, 'scopesAjax'])->name('admin.scopes.ajax');
    Route::get('/admin/permissions/ajax', [AdminController::class, 'permissionsAjax'])->name('admin.permissions.ajax');
    Route::get('/admin/masters', [AdminController::class, 'mastersIndex'])->name('admin.masters.index');
    Route::post('/admin/departments', [AdminController::class, 'storeDepartment'])->name('admin.departments.store');
    Route::put('/admin/departments/{id}', [AdminController::class, 'updateDepartment'])->name('admin.departments.update');
    Route::delete('/admin/departments/{id}', [AdminController::class, 'destroyDepartment'])->name('admin.departments.destroy');
    Route::put('/admin/permissions/{id}', [RoleController::class, 'updatePermission'])->name('admin.permissions.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
