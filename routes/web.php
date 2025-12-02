<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    // Ruta de métricas
    Route::get('/metricas', [MetricsController::class, 'index'])->name('metrics.index');
    Route::get('/metricas/expandir/{chartId}', [MetricsController::class, 'expand'])->name('metrics.expand');
    Route::post('/user/update-estado', [UserController::class, 'updateEstado'])->name('user.update-estado');
    Route::post('/user/update-theme', [UserController::class, 'updateThemePreference'])->name('user.update-theme');
    
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/profile/update-banner', [ProfileController::class, 'updateBanner'])->name('profile.update-banner');
    Route::delete('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    Route::delete('/profile/delete-banner', [ProfileController::class, 'deleteBanner'])->name('profile.delete-banner');

    // ========================================
    // MÓDULO: INVENTARIO
    // ========================================
    
    // Rutas específicas que deben ir ANTES del resource para evitar conflictos
    Route::get('/inventory/download-template', [InventoryController::class, 'downloadTemplate'])
        ->name('inventory.download-template');
    
    // Acciones masivas (deben ir ANTES del resource)
    Route::delete('/inventory/bulk-delete', [InventoryController::class, 'bulkDelete'])
        ->name('inventory.bulk-delete');
    Route::post('/inventory/bulk-restore', [InventoryController::class, 'bulkRestore'])
        ->name('inventory.bulk-restore');
    
    // Ruta Resource de inventario (incluye CRUD completo)
    Route::resource('inventory', InventoryController::class)->names([
        'index' => 'inventory.index',
        'create' => 'inventory.create',
        'store' => 'inventory.store',
        'show' => 'inventory.show',
        'edit' => 'inventory.edit',
        'update' => 'inventory.update',
        'destroy' => 'inventory.destroy'
    ]);
    
    // Rutas adicionales para gestión de bienes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        
        // Reactivar bien dado de baja
        Route::post('/{id}/restore', [InventoryController::class, 'restore'])
            ->name('restore');
        
        // Eliminar imagen individual
        Route::delete('/{inventory}/image/{imageIndex}', [InventoryController::class, 'deleteImage'])
            ->name('delete-image');
        
        // Tarjetas de Responsabilidad
        Route::get('/{inventory}/responsibility-card', [InventoryController::class, 'showResponsibilityCard'])
            ->name('responsibility-card.show');
        Route::post('/{inventory}/assign-department', [InventoryController::class, 'assignDepartment'])
            ->name('assign-department');
        Route::post('/responsibility-card/print', [InventoryController::class, 'printResponsibilityCard'])
            ->name('responsibility-card.print');
        
        // Gestión de Usuarios en Tarjeta de Responsabilidad
        Route::post('/responsibility-card/{card}/assign-user', [InventoryController::class, 'assignUser'])
            ->name('responsibility-card.assign-user');
        Route::delete('/responsibility-card/{card}/remove-user/{user}', [InventoryController::class, 'removeUser'])
            ->name('responsibility-card.remove-user');
        
        // Dictámenes
        Route::get('/appraisals/pending', [InventoryController::class, 'pendingAppraisals'])
            ->name('appraisals.pending');
        Route::get('/appraisals/completed', [InventoryController::class, 'completedAppraisals'])
            ->name('appraisals.completed');
        Route::post('/appraisals/{appraisal}/upload', [InventoryController::class, 'uploadAppraisal'])
            ->name('appraisals.upload');
        Route::get('/appraisals/{appraisal}/print', [InventoryController::class, 'printAppraisal'])
            ->name('appraisals.print');
        Route::get('/appraisals/{appraisal}/view', [InventoryController::class, 'viewAppraisal'])
            ->name('appraisals.view');
        
        // Traspasos de Bienes
        Route::get('/transfers', [InventoryController::class, 'transfersIndex'])
            ->name('transfers.index');
        Route::get('/transfers/create', [InventoryController::class, 'createTransfer'])
            ->name('transfers.create');
        Route::post('/transfers', [InventoryController::class, 'storeTransfer'])
            ->name('transfers.store');
        Route::get('/transfers/{transfer}', [InventoryController::class, 'showTransfer'])
            ->name('transfers.show');
        Route::get('/transfers/{transfer}/print', [InventoryController::class, 'printTransfer'])
            ->name('transfers.print');
        Route::post('/transfers/{transfer}/upload', [InventoryController::class, 'uploadSignedTransfer'])
            ->name('transfers.upload');
        
        // Generar PDF/Excel e importar bienes seleccionados
        Route::post('/generate-pdf', [InventoryController::class, 'generatePdf'])
            ->name('generate-pdf');
        Route::post('/generate-excel', [InventoryController::class, 'generateExcel'])
            ->name('generate-excel');
        Route::post('/import', [InventoryController::class, 'import'])
            ->name('import');
        Route::post('/columns/preferences', [InventoryController::class, 'saveColumnPreferences'])
            ->name('columns-preferences');
        Route::post('/get-selected-items', [InventoryController::class, 'getSelectedItems'])
            ->name('get-selected-items');
    });
});
