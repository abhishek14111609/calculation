# Bank Passbook/Statement Feature - Implementation Report

## 📋 Overview
This report outlines the implementation plan for a **Bank Passbook/Statement** feature that displays all transactions (deposits, withdrawals, settlements) in a unified, chronological view with filtering and export capabilities.

---

## 🎯 Feature Requirements

### Core Functionality
1. **Unified Transaction View**: Display all transaction types in a single chronological list
2. **Running Balance**: Show opening balance, running balance after each transaction, and closing balance
3. **Date Filtering**: Filter transactions by date range
4. **Bank Filtering**: Filter by specific bank or view all banks
5. **Transaction Type Filtering**: Filter by deposits, withdrawals, settlements, or all
6. **Export Options**: Export to Excel/PDF
7. **Print-Ready Layout**: Clean design suitable for printing

### Visual Design
- **Premium UI**: Match existing fintech-grade design
- **Color Coding**: 
  - Green for deposits (credit)
  - Red for withdrawals (debit)
  - Blue for settlements
- **Icons**: Transaction type indicators
- **Running Balance Column**: Prominently displayed
- **Date Grouping**: Optional grouping by date

---

## 🗂️ Database Structure (Existing Tables)

### Tables to Query:
1. **`deposits`** table
   - id, bank_id, date, amount, source_name, utr, remark, created_at
   
2. **`withdrawals`** table
   - id, bank_id, date, amount, status, source_name, utr, remark, created_at
   
3. **`settlements`** table
   - id, from_bank_id, to_bank_id, date, amount, utr, remark, created_at
   
4. **`bank_closings`** table
   - id, bank_id, date, actual_closing, created_at, updated_at

### No Database Changes Required ✅

---

## 📁 File Structure

### New Files to Create:

```
app/
├── Http/
│   └── Controllers/
│       └── PassbookController.php          [NEW]
│
├── Services/
│   └── PassbookService.php                 [NEW]
│
└── Exports/
    └── PassbookExport.php                  [NEW]

resources/
└── views/
    └── reconciliation/
        └── passbook.blade.php              [NEW]

routes/
└── web.php                                 [MODIFY - Add routes]
```

---

## 💻 Implementation Details

### 1. **Route Definition** (`routes/web.php`)

```php
Route::prefix('reconciliation')->name('reconciliation.')->group(function () {
    // Existing routes...
    
    // NEW: Passbook routes
    Route::get('/passbook', [PassbookController::class, 'index'])->name('passbook');
    Route::get('/passbook/export', [PassbookController::class, 'export'])->name('passbook.export');
    Route::get('/passbook/pdf', [PassbookController::class, 'exportPdf'])->name('passbook.pdf');
});
```

---

### 2. **Controller** (`app/Http/Controllers/PassbookController.php`)

```php
<?php

namespace App\Http\Controllers;

use App\Services\PassbookService;
use App\Exports\PassbookExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PassbookController extends Controller
{
    protected $passbookService;

    public function __construct(PassbookService $passbookService)
    {
        $this->passbookService = $passbookService;
    }

    /**
     * Display passbook/statement
     */
    public function index(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'transaction_type' => $request->input('transaction_type'), // all, deposit, withdrawal, settlement
        ];

        // Get all banks for filter dropdown
        $banks = \App\Models\Bank::orderBy('name')->get();

        // Get passbook data
        $passbookData = $this->passbookService->getPassbookData($filters);

        return view('reconciliation.passbook', compact('passbookData', 'banks', 'filters'));
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'transaction_type' => $request->input('transaction_type'),
        ];

        $passbookData = $this->passbookService->getPassbookData($filters);
        
        return Excel::download(
            new PassbookExport($passbookData), 
            'passbook_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'transaction_type' => $request->input('transaction_type'),
        ];

        $banks = \App\Models\Bank::orderBy('name')->get();
        $passbookData = $this->passbookService->getPassbookData($filters);

        $pdf = Pdf::loadView('reconciliation.passbook-pdf', compact('passbookData', 'banks', 'filters'));
        
        return $pdf->download('passbook_' . now()->format('Y-m-d_His') . '.pdf');
    }
}
```

---

### 3. **Service Class** (`app/Services/PassbookService.php`)

```php
<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Settlement;
use App\Models\BankClosing;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PassbookService
{
    /**
     * Get unified passbook data with running balance
     */
    public function getPassbookData(array $filters): array
    {
        $bankId = $filters['bank_id'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $transactionType = $filters['transaction_type'] ?? 'all';

        // Get opening balance (from previous closing or 0)
        $openingBalance = $this->getOpeningBalance($bankId, $startDate);

        // Collect all transactions
        $transactions = collect();

        // Add deposits
        if (in_array($transactionType, ['all', 'deposit'])) {
            $deposits = $this->getDeposits($bankId, $startDate, $endDate);
            $transactions = $transactions->merge($deposits);
        }

        // Add withdrawals
        if (in_array($transactionType, ['all', 'withdrawal'])) {
            $withdrawals = $this->getWithdrawals($bankId, $startDate, $endDate);
            $transactions = $transactions->merge($withdrawals);
        }

        // Add settlements
        if (in_array($transactionType, ['all', 'settlement'])) {
            $settlements = $this->getSettlements($bankId, $startDate, $endDate);
            $transactions = $transactions->merge($settlements);
        }

        // Sort by date and time
        $transactions = $transactions->sortBy([
            ['date', 'asc'],
            ['created_at', 'asc']
        ])->values();

        // Calculate running balance
        $runningBalance = $openingBalance;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['amount'];
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });

        return [
            'opening_balance' => $openingBalance,
            'closing_balance' => $runningBalance,
            'transactions' => $transactions,
            'total_credit' => $transactions->where('type', 'deposit')->sum('amount'),
            'total_debit' => abs($transactions->whereIn('type', ['withdrawal', 'settlement_out'])->sum('amount')),
            'transaction_count' => $transactions->count(),
        ];
    }

    /**
     * Get opening balance from previous closing
     */
    protected function getOpeningBalance($bankId, $startDate): float
    {
        if (!$bankId || !$startDate) {
            return 0;
        }

        $closing = BankClosing::where('bank_id', $bankId)
            ->where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->first();

        return $closing ? $closing->actual_closing : 0;
    }

    /**
     * Get deposits
     */
    protected function getDeposits($bankId, $startDate, $endDate): Collection
    {
        $query = Deposit::with('bank');

        if ($bankId) {
            $query->where('bank_id', $bankId);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->get()->map(function ($deposit) {
            return [
                'id' => $deposit->id,
                'date' => $deposit->date,
                'type' => 'deposit',
                'type_label' => 'Deposit',
                'description' => 'Deposit' . ($deposit->source_name ? ' from ' . $deposit->source_name : ''),
                'bank_name' => $deposit->bank->name,
                'utr' => $deposit->utr,
                'amount' => $deposit->amount, // Positive for credit
                'remark' => $deposit->remark,
                'created_at' => $deposit->created_at,
            ];
        });
    }

    /**
     * Get withdrawals
     */
    protected function getWithdrawals($bankId, $startDate, $endDate): Collection
    {
        $query = Withdrawal::with('bank');

        if ($bankId) {
            $query->where('bank_id', $bankId);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        // Only completed withdrawals affect balance
        $query->where('status', 'completed');

        return $query->get()->map(function ($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'date' => $withdrawal->date,
                'type' => 'withdrawal',
                'type_label' => 'Withdrawal',
                'description' => 'Withdrawal' . ($withdrawal->source_name ? ' to ' . $withdrawal->source_name : ''),
                'bank_name' => $withdrawal->bank->name,
                'utr' => $withdrawal->utr,
                'amount' => -$withdrawal->amount, // Negative for debit
                'remark' => $withdrawal->remark,
                'status' => $withdrawal->status,
                'created_at' => $withdrawal->created_at,
            ];
        });
    }

    /**
     * Get settlements
     */
    protected function getSettlements($bankId, $startDate, $endDate): Collection
    {
        $query = Settlement::with(['fromBank', 'toBank']);

        if ($bankId) {
            // Settlements where this bank is either sender or receiver
            $query->where(function ($q) use ($bankId) {
                $q->where('from_bank_id', $bankId)
                  ->orWhere('to_bank_id', $bankId);
            });
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->get()->flatMap(function ($settlement) use ($bankId) {
            $transactions = [];

            // If filtering by bank, only show relevant side
            if ($bankId) {
                if ($settlement->from_bank_id == $bankId) {
                    // Money going OUT
                    $transactions[] = [
                        'id' => $settlement->id,
                        'date' => $settlement->date,
                        'type' => 'settlement_out',
                        'type_label' => 'Settlement OUT',
                        'description' => 'Settlement to ' . $settlement->toBank->name,
                        'bank_name' => $settlement->fromBank->name,
                        'utr' => $settlement->utr,
                        'amount' => -$settlement->amount, // Negative
                        'remark' => $settlement->remark,
                        'created_at' => $settlement->created_at,
                    ];
                }

                if ($settlement->to_bank_id == $bankId) {
                    // Money coming IN
                    $transactions[] = [
                        'id' => $settlement->id,
                        'date' => $settlement->date,
                        'type' => 'settlement_in',
                        'type_label' => 'Settlement IN',
                        'description' => 'Settlement from ' . $settlement->fromBank->name,
                        'bank_name' => $settlement->toBank->name,
                        'utr' => $settlement->utr,
                        'amount' => $settlement->amount, // Positive
                        'remark' => $settlement->remark,
                        'created_at' => $settlement->created_at,
                    ];
                }
            } else {
                // Show both sides if no bank filter
                $transactions[] = [
                    'id' => $settlement->id,
                    'date' => $settlement->date,
                    'type' => 'settlement',
                    'type_label' => 'Settlement',
                    'description' => $settlement->fromBank->name . ' → ' . $settlement->toBank->name,
                    'bank_name' => 'Settlement',
                    'utr' => $settlement->utr,
                    'amount' => 0, // Neutral in all-banks view
                    'remark' => $settlement->remark,
                    'created_at' => $settlement->created_at,
                ];
            }

            return $transactions;
        });
    }
}
```

---

### 4. **View File** (`resources/views/reconciliation/passbook.blade.php`)

```blade
@extends('layouts.app')

@section('title', 'Bank Passbook / Statement')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Bank Passbook / Statement
            </h2>
            <p class="text-sm text-gray-500 mt-1">View all transactions in chronological order with running balance</p>
        </div>
    </div>

    <form method="GET" action="{{ route('reconciliation.passbook') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Bank</label>
                <select name="bank_id" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                    <option value="">All Banks</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}" {{ ($filters['bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Transaction Type</label>
                <select name="transaction_type" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                    <option value="all" {{ ($filters['transaction_type'] ?? 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="deposit" {{ ($filters['transaction_type'] ?? '') == 'deposit' ? 'selected' : '' }}>Deposits Only</option>
                    <option value="withdrawal" {{ ($filters['transaction_type'] ?? '') == 'withdrawal' ? 'selected' : '' }}>Withdrawals Only</option>
                    <option value="settlement" {{ ($filters['transaction_type'] ?? '') == 'settlement' ? 'selected' : '' }}>Settlements Only</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                    Apply Filters
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <a href="{{ route('reconciliation.passbook') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-all">
                Clear Filters
            </a>
            <div class="flex gap-2">
                <a href="{{ route('reconciliation.passbook.export', request()->query()) }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-sm hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
                <a href="{{ route('reconciliation.passbook.pdf', request()->query()) }}" class="px-5 py-2.5 bg-red-50 text-red-700 rounded-xl font-semibold text-sm hover:bg-red-100 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-blue-500">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Opening Balance</p>
        <p class="text-2xl font-bold text-blue-600 currency">₹{{ number_format($passbookData['opening_balance'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-emerald-500">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Credit</p>
        <p class="text-2xl font-bold text-emerald-600 currency">₹{{ number_format($passbookData['total_credit'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-red-500">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Debit</p>
        <p class="text-2xl font-bold text-red-600 currency">₹{{ number_format($passbookData['total_debit'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-indigo-500">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Closing Balance</p>
        <p class="text-2xl font-bold text-indigo-600 currency">₹{{ number_format($passbookData['closing_balance'], 2) }}</p>
    </div>
</div>

{{-- Transactions Table --}}
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">UTR</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Debit (₹)</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Credit (₹)</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Balance (₹)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($passbookData['transactions'] as $transaction)
                    <tr class="table-row">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if($transaction['type'] == 'deposit')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                    Deposit
                                </span>
                            @elseif($transaction['type'] == 'withdrawal')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                    Withdrawal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    Settlement
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction['description'] }}</td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $transaction['utr'] ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($transaction['amount'] < 0)
                                <span class="font-semibold text-red-600 currency">{{ number_format(abs($transaction['amount']), 2) }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($transaction['amount'] > 0)
                                <span class="font-semibold text-emerald-600 currency">{{ number_format($transaction['amount'], 2) }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-indigo-600 text-base currency">{{ number_format($transaction['running_balance'], 2) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <p class="text-gray-600 font-medium">No transactions found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 text-center text-sm text-gray-500">
    Showing {{ $passbookData['transaction_count'] }} transactions
</div>
@endsection
```

---

### 5. **Excel Export** (`app/Exports/PassbookExport.php`)

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PassbookExport implements FromCollection, WithHeadings, WithStyles
{
    protected $passbookData;

    public function __construct($passbookData)
    {
        $this->passbookData = $passbookData;
    }

    public function collection()
    {
        return collect($this->passbookData['transactions'])->map(function ($transaction) {
            return [
                'date' => \Carbon\Carbon::parse($transaction['date'])->format('d-m-Y'),
                'type' => $transaction['type_label'],
                'description' => $transaction['description'],
                'utr' => $transaction['utr'] ?? '-',
                'debit' => $transaction['amount'] < 0 ? abs($transaction['amount']) : '',
                'credit' => $transaction['amount'] > 0 ? $transaction['amount'] : '',
                'balance' => $transaction['running_balance'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Description',
            'UTR',
            'Debit (₹)',
            'Credit (₹)',
            'Balance (₹)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
```

---

## 🎨 UI/UX Features

### Design Elements:
1. **Filter Panel**: Bank, Transaction Type, Date Range filters
2. **Summary Cards**: Opening Balance, Total Credit, Total Debit, Closing Balance
3. **Transaction Table**: 
   - Date
   - Type (with color-coded badges)
   - Description
   - UTR
   - Debit column (red)
   - Credit column (green)
   - Running Balance column (bold, prominent)
4. **Export Buttons**: Excel and PDF export
5. **Empty State**: Friendly message when no transactions found

### Color Coding:
- **Green**: Deposits/Credits
- **Red**: Withdrawals/Debits
- **Blue**: Settlements
- **Indigo**: Running Balance

---

## 📊 Sample Output

### Example Passbook View:

```
Opening Balance: ₹50,000.00

Date       | Type       | Description              | Debit    | Credit   | Balance
-----------|------------|--------------------------|----------|----------|----------
01 Feb 26  | Deposit    | Deposit from Customer A  | -        | 10,000   | 60,000
02 Feb 26  | Withdrawal | Withdrawal to Vendor B   | 5,000    | -        | 55,000
03 Feb 26  | Settlement | Settlement to HDFC Bank  | 15,000   | -        | 40,000
04 Feb 26  | Deposit    | Deposit from Customer C  | -        | 25,000   | 65,000

Closing Balance: ₹65,000.00
Total Credit: ₹35,000.00
Total Debit: ₹20,000.00
```

---

## 🔄 Navigation Integration

### Add to Navigation Bar (`layouts/app.blade.php`):

```blade
<a href="{{ route('reconciliation.passbook') }}" 
   class="nav-pill {{ request()->routeIs('reconciliation.passbook') ? 'active' : '' }}">
    Passbook
</a>
```

---

## ✅ Implementation Checklist

- [ ] Create `PassbookController.php`
- [ ] Create `PassbookService.php`
- [ ] Create `PassbookExport.php`
- [ ] Create `passbook.blade.php` view
- [ ] Add routes to `web.php`
- [ ] Add navigation link in `app.blade.php`
- [ ] Test with sample data
- [ ] Test Excel export
- [ ] Test PDF export (optional, requires `barryvdh/laravel-dompdf`)
- [ ] Test filters (bank, date range, transaction type)
- [ ] Verify running balance calculations

---

## 🚀 Benefits

1. **Unified View**: All transactions in one place
2. **Running Balance**: See balance after each transaction
3. **Flexible Filtering**: Filter by bank, date, transaction type
4. **Export Options**: Excel and PDF for record-keeping
5. **Print-Ready**: Clean design suitable for printing
6. **Premium UI**: Matches existing fintech-grade design
7. **No Database Changes**: Uses existing tables

---

## 📝 Notes

- **Opening Balance**: Fetched from the most recent `bank_closing` record before the start date
- **Completed Withdrawals Only**: Only completed withdrawals affect the running balance
- **Settlement Handling**: Shows settlements as IN/OUT when filtering by specific bank
- **Date Sorting**: Transactions sorted chronologically with secondary sort by creation time
- **Performance**: Consider pagination for large datasets (add `->paginate(50)` if needed)

---

## 🎯 Next Steps

Once you approve this design, I will:
1. Create all the files listed above
2. Implement the complete functionality
3. Add the navigation link
4. Test the feature end-to-end

**Ready to implement? Just say "YES" and I'll create all the files!**
