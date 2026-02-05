<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\PassbookController;

// Main Reconciliation Dashboard
Route::get('/', [ReconciliationController::class, 'index'])->name('reconciliation.index');

// Secondary Pages
Route::get('/deposits', [ReconciliationController::class, 'deposits'])->name('reconciliation.deposits');
Route::get('/withdrawals', [ReconciliationController::class, 'withdrawals'])->name('reconciliation.withdrawals');
Route::get('/settlements', [ReconciliationController::class, 'settlements'])->name('reconciliation.settlements');
Route::get('/closings', [ReconciliationController::class, 'closings'])->name('reconciliation.closings');

// Passbook / Statement
Route::get('/passbook', [PassbookController::class, 'index'])->name('reconciliation.passbook');
Route::get('/passbook/export', [PassbookController::class, 'export'])->name('reconciliation.passbook.export');

// Smart Upload (Centralized)
Route::get('/upload', [ReconciliationController::class, 'showUpload'])->name('reconciliation.upload');
Route::post('/upload', [ReconciliationController::class, 'uploadSmart'])->name('reconciliation.upload.process');
Route::get('/upload/report', [ReconciliationController::class, 'showUploadReport'])->name('reconciliation.upload.report');

// Closing Update
Route::post('/closings/update', [ReconciliationController::class, 'updateClosing'])->name('reconciliation.closings.update');

// Export Routes
Route::get('/export/reconciliation', [ReconciliationController::class, 'exportReconciliation'])->name('reconciliation.export');
Route::get('/export/deposits', [ReconciliationController::class, 'exportDeposits'])->name('reconciliation.deposits.export');
Route::get('/export/withdrawals', [ReconciliationController::class, 'exportWithdrawals'])->name('reconciliation.withdrawals.export');
Route::get('/export/settlements', [ReconciliationController::class, 'exportSettlements'])->name('reconciliation.settlements.export');

// Sample Template Downloads
Route::get('/samples/deposits', [ReconciliationController::class, 'downloadSampleDeposits'])->name('reconciliation.samples.deposits');
Route::get('/samples/withdrawals', [ReconciliationController::class, 'downloadSampleWithdrawals'])->name('reconciliation.samples.withdrawals');
Route::get('/samples/settlements', [ReconciliationController::class, 'downloadSampleSettlements'])->name('reconciliation.samples.settlements');
Route::get('/samples/smart-upload', [ReconciliationController::class, 'downloadSmartSample'])->name('reconciliation.samples.smart');
Route::get('/samples/comprehensive', [ReconciliationController::class, 'downloadComprehensiveSample'])->name('reconciliation.samples.comprehensive');