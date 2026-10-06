<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MasterFrameworkController;
use App\Http\Controllers\MasterKategoriController;
use App\Http\Controllers\MasterIndikatorController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentDetailController;
use App\Http\Controllers\MasterRoleController;
use App\Http\Controllers\MasterModuleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArahanController;
use App\Http\Controllers\ArahanDetailController;

Route::middleware('auth')->group(function () {
Route::resource('employee', EmployeeController::class);
Route::resource('master_framework', MasterFrameworkController::class);
Route::resource('master_kategori', MasterKategoriController::class);
Route::resource('master_indikator', MasterIndikatorController::class);

// ASESMEN////
Route::resource('assessment', AssessmentController::class);
Route::get(
    'assessment-detail/{assessment_id}',
    [AssessmentDetailController::class, 'index']
)->name('assessment_detail.index');

Route::post(
    'assessment-detail/store',
    [AssessmentDetailController::class, 'store']
)->name('assessment_detail.store');

Route::get(
    'assessment-detail/edit/{id}',
    [AssessmentDetailController::class, 'edit']
)->name('assessment_detail.edit');

Route::put(
    'assessment-detail/update/{id}',
    [AssessmentDetailController::class, 'update']
)->name('assessment_detail.update');

Route::delete(
    'assessment-detail/delete/{id}',
    [AssessmentDetailController::class, 'destroy']
)->name('assessment_detail.destroy');

//ARAHAN//////
Route::resource('arahan', ArahanController::class);

Route::get(
    'arahan-detail/{arahan_id}',
    [ArahanDetailController::class, 'index']
)->name('arahan_detail.index');

Route::get(
    'arahan-detail/create/{arahan_id}',
    [ArahanDetailController::class, 'create']
)->name('arahan_detail.create');

Route::post(
    'arahan-detail/store',
    [ArahanDetailController::class, 'store']
)->name('arahan_detail.store');

Route::get(
    'arahan-detail/edit/{id}',
    [ArahanDetailController::class, 'edit']
)->name('arahan_detail.edit');

Route::put(
    'arahan-detail/update/{id}',
    [ArahanDetailController::class, 'update']
)->name('arahan_detail.update');

Route::delete(
    'arahan-detail/delete/{id}',
    [ArahanDetailController::class, 'destroy']
)->name('arahan_detail.destroy');
});

/////////////////

Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login-process', [AuthController::class, 'loginProcess'])
    ->name('login.process');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard');

    // ROLE
    Route::resource('master_role', MasterRoleController::class);
    Route::resource('master_module', MasterModuleController::class);
    Route::resource('role_permission', RolePermissionController::class);
});

// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('home');

// Kept as a placeholder until the password-reset flow is implemented.
// Route::view is cache-safe and does not depend on a missing template controller.
Route::view('/auth/forgot-password-basic', 'content.authentications.auth-forgot-password-basic')
    ->name('auth-reset-password-basic');
