<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReconciliationController;

// Main Reconciliation Dashboard
Route::get('/', [ReconciliationController::class, 'index'])->name('reconciliation.index');

// Secondary Pages
Route::get('/deposits', [ReconciliationController::class, 'deposits'])->name('reconciliation.deposits');
Route::get('/withdrawals', [ReconciliationController::class, 'withdrawals'])->name('reconciliation.withdrawals');
Route::get('/settlements', [ReconciliationController::class, 'settlements'])->name('reconciliation.settlements');
Route::get('/closings', [ReconciliationController::class, 'closings'])->name('reconciliation.closings');

// Upload Routes
Route::post('/deposits/upload', [ReconciliationController::class, 'uploadDeposits'])->name('reconciliation.deposits.upload');
Route::post('/withdrawals/upload', [ReconciliationController::class, 'uploadWithdrawals'])->name('reconciliation.withdrawals.upload');
Route::post('/settlements/upload', [ReconciliationController::class, 'uploadSettlements'])->name('reconciliation.settlements.upload');

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