<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;

// Main Dashboard Route
Route::get('/', [CalculationController::class, 'index'])->name('calculation.index');

// Upload Routes
Route::post('/upload-customers', [CalculationController::class, 'uploadCustomers'])->name('calculation.upload.customers');
Route::post('/upload-transactions', [CalculationController::class, 'uploadTransactions'])->name('calculation.upload.transactions');
Route::delete('/customers/{customerId}', [CalculationController::class, 'deleteCustomer'])->name('calculation.delete.customer');
Route::delete('/customers-bulk', [CalculationController::class, 'bulkDeleteCustomers'])->name('calculation.bulk.delete.customers');

// Export Routes
Route::get('/export-excel', [CalculationController::class, 'exportExcel'])->name('calculation.export.excel');
Route::get('/export-csv', [CalculationController::class, 'exportCsv'])->name('calculation.export.csv');

// Sample Download Routes (XLSX)
Route::get('/samples/customers', [CalculationController::class, 'downloadSampleCustomers'])->name('calculation.sample.customers');
Route::get('/samples/transactions', [CalculationController::class, 'downloadSampleTransactions'])->name('calculation.sample.transactions');

// Ledger Routes
Route::get('/master-log', [CalculationController::class, 'masterLog'])->name('calculation.master_log');
Route::get('/master-log/export', [CalculationController::class, 'exportMasterLog'])->name('calculation.master_log.export');
Route::get('/ledger/{customerId}', [CalculationController::class, 'ledger'])->name('calculation.ledger');
Route::get('/ledger/{customerId}/export', [CalculationController::class, 'exportStatement'])->name('calculation.ledger.export');