<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\PassbookController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\BankClosingController;

// Main Reconciliation Dashboard
Route::get('/', [ReconciliationController::class, 'index'])->name('reconciliation.index');

// CRUD Resources (maintaining backward compatibility with existing views)
Route::get('/deposits', [DepositController::class, 'index'])->name('reconciliation.deposits');
Route::get('/deposits/create', [DepositController::class, 'create'])->name('deposits.create');
Route::post('/deposits', [DepositController::class, 'store'])->name('deposits.store');
Route::get('/deposits/{deposit}/edit', [DepositController::class, 'edit'])->name('deposits.edit');
Route::put('/deposits/{deposit}', [DepositController::class, 'update'])->name('deposits.update');
Route::delete('/deposits/{deposit}', [DepositController::class, 'destroy'])->name('deposits.destroy');
Route::post('/deposits/{id}/restore', [DepositController::class, 'restore'])->name('deposits.restore');

Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('reconciliation.withdrawals');
Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
Route::get('/withdrawals/{withdrawal}/edit', [WithdrawalController::class, 'edit'])->name('withdrawals.edit');
Route::put('/withdrawals/{withdrawal}', [WithdrawalController::class, 'update'])->name('withdrawals.update');
Route::delete('/withdrawals/{withdrawal}', [WithdrawalController::class, 'destroy'])->name('withdrawals.destroy');
Route::post('/withdrawals/{id}/restore', [WithdrawalController::class, 'restore'])->name('withdrawals.restore');

Route::get('/settlements', [SettlementController::class, 'index'])->name('reconciliation.settlements');
Route::get('/settlements/create', [SettlementController::class, 'create'])->name('settlements.create');
Route::post('/settlements', [SettlementController::class, 'store'])->name('settlements.store');
Route::get('/settlements/{settlement}/edit', [SettlementController::class, 'edit'])->name('settlements.edit');
Route::put('/settlements/{settlement}', [SettlementController::class, 'update'])->name('settlements.update');
Route::delete('/settlements/{settlement}', [SettlementController::class, 'destroy'])->name('settlements.destroy');
Route::post('/settlements/{id}/restore', [SettlementController::class, 'restore'])->name('settlements.restore');
Route::get('/deposits/export', [DepositController::class, 'export'])->name('deposits.export');
Route::get('/withdrawals/export', [WithdrawalController::class, 'export'])->name('withdrawals.export');
Route::get('/settlements/export', [SettlementController::class, 'export'])->name('settlements.export');

Route::get('/closings', [BankClosingController::class, 'index'])->name('reconciliation.closings');
Route::post('/closings', [BankClosingController::class, 'store'])->name('closings.store');
Route::put('/closings/{closing}', [BankClosingController::class, 'update'])->name('closings.update');
Route::delete('/closings/{closing}', [BankClosingController::class, 'destroy'])->name('closings.destroy');
Route::post('/closings/{id}/restore', [BankClosingController::class, 'restore'])->name('closings.restore');
Route::get('/closings/export', [BankClosingController::class, 'export'])->name('closings.export');

// Passbook / Statement
Route::get('/passbook', [PassbookController::class, 'index'])->name('reconciliation.passbook');
Route::get('/passbook/export', [PassbookController::class, 'export'])->name('reconciliation.passbook.export');

// Smart Upload (Centralized)
Route::get('/upload', [ReconciliationController::class, 'showUpload'])->name('reconciliation.upload');
Route::post('/upload', [ReconciliationController::class, 'uploadSmart'])->name('reconciliation.upload.process');
Route::get('/upload/report', [ReconciliationController::class, 'showUploadReport'])->name('reconciliation.upload.report');

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