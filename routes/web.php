<?php

use App\Http\Controllers\BulkEmailController;
use App\Http\Controllers\RecipientsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [BulkEmailController::class, 'index']);

// Bulk Email Routes
Route::prefix('bulk-email')->name('bulk-email.')->group(function () {
    Route::get('/', [BulkEmailController::class, 'index'])->name('index');
    Route::get('/create', [BulkEmailController::class, 'create'])->name('create');
    Route::post('/', [BulkEmailController::class, 'store'])->name('store');
    Route::get('/{campaign}', [BulkEmailController::class, 'show'])->name('show');
    Route::post('/{campaign}/send', [BulkEmailController::class, 'send'])->name('send');
    Route::patch('/{campaign}/complete', [BulkEmailController::class, 'markComplete'])->name('complete');
    Route::delete('/{campaign}', [BulkEmailController::class, 'destroy'])->name('destroy');
});

// Recipients Routes
Route::prefix('recipients')->name('recipients.')->group(function () {
    Route::get('/', [RecipientsController::class, 'index'])->name('index');
    Route::get('/create', [RecipientsController::class, 'create'])->name('create');
    Route::post('/', [RecipientsController::class, 'store'])->name('store');
    Route::post('/bulk-import', [RecipientsController::class, 'bulkImport'])->name('bulk-import');
});


Route::get('/run-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration run successfully';
});
