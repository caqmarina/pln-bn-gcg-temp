<?php

use App\Http\Controllers\ArahanController;
use App\Http\Controllers\ArahanDetailController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentDetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MasterFrameworkController;
use App\Http\Controllers\MasterIndikatorController;
use App\Http\Controllers\MasterKategoriController;
use App\Http\Controllers\MasterModuleController;
use App\Http\Controllers\MasterRoleController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Admin-only master and employee management.
    Route::middleware('role:Admin')->group(function () {
        Route::resource('employee', EmployeeController::class);
        Route::resource('master_framework', MasterFrameworkController::class);
        Route::resource('master_kategori', MasterKategoriController::class);
        Route::resource('master_indikator', MasterIndikatorController::class);
    });

    // Assessment: User reads, Assessor/Tim GCG update details, Tim GCG creates.
    Route::resource('assessment', AssessmentController::class)
        ->only(['create', 'store'])
        ->middleware('permission:assessment,create');
    Route::resource('assessment', AssessmentController::class)
        ->only(['index'])
        ->middleware('permission:assessment,read');
    Route::resource('assessment', AssessmentController::class)
        ->only(['edit', 'update'])
        ->middleware('role:Admin');
    Route::resource('assessment', AssessmentController::class)
        ->only(['destroy'])
        ->middleware('role:Admin');

    Route::get(
        'assessment-detail/{assessment_id}',
        [AssessmentDetailController::class, 'index']
    )->middleware('permission:assessment,read')->name('assessment_detail.index');

    Route::put(
        'assessment-detail/update/{id}',
        [AssessmentDetailController::class, 'update']
    )->middleware('permission:assessment,update')->name('assessment_detail.update');

    // Arahan is viewable by all authenticated roles; only Admin edits it.
    Route::resource('arahan', ArahanController::class)
        ->only(['index', 'show'])
        ->middleware('permission:arahan,read');
    Route::middleware('role:Admin')->group(function () {
        Route::post('arahan/import', [ArahanController::class, 'import'])->name('arahan.import');
        Route::resource('arahan', ArahanController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::get(
        'arahan-detail/{arahan_id}',
        [ArahanDetailController::class, 'index']
    )->middleware('permission:arahan,read')->name('arahan_detail.index');

    Route::middleware('role:Admin')->group(function () {
        Route::get('arahan-detail/create/{arahan_id}', [ArahanDetailController::class, 'create'])
            ->name('arahan_detail.create');
        Route::post('arahan-detail/store', [ArahanDetailController::class, 'store'])
            ->name('arahan_detail.store');
        Route::get('arahan-detail/edit/{id}', [ArahanDetailController::class, 'edit'])
            ->name('arahan_detail.edit');
        Route::put('arahan-detail/update/{id}', [ArahanDetailController::class, 'update'])
            ->name('arahan_detail.update');
        Route::delete('arahan-detail/delete/{id}', [ArahanDetailController::class, 'destroy'])
            ->name('arahan_detail.destroy');
    });
});

// ///////////////

Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login-process', [AuthController::class, 'loginProcess'])
    ->name('login.process');

Route::get('/dashboard', [Analytics::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    // ROLE
    Route::resource('master_role', MasterRoleController::class);
    Route::resource('master_module', MasterModuleController::class);
    Route::resource('role_permission', RolePermissionController::class);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Main Page Route
Route::get('/', fn () => redirect()->route('dashboard'))
    ->middleware('auth')
    ->name('home');

// Kept as a placeholder until the password-reset flow is implemented.
// Route::view is cache-safe and does not depend on a missing template controller.
Route::view('/auth/forgot-password-basic', 'content.authentications.auth-forgot-password-basic')
    ->name('auth-reset-password-basic');
