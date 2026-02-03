<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;

// Main Dashboard Route
Route::get('/', [CalculationController::class, 'index'])->name('calculation.index');

// Upload Routes
Route::post('/upload-customers', [CalculationController::class, 'uploadCustomers'])->name('calculation.upload.customers');
Route::post('/upload-transactions', [CalculationController::class, 'uploadTransactions'])->name('calculation.upload.transactions');
Route::delete('/customers/{customerId}', [CalculationController::class, 'deleteCustomer'])->name('calculation.delete.customer');

// Export Routes
Route::get('/export-excel', [CalculationController::class, 'exportExcel'])->name('calculation.export.excel');
Route::get('/export-csv', [CalculationController::class, 'exportCsv'])->name('calculation.export.csv');