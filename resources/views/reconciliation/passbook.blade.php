@extends('layouts.app')

@section('title', 'Bank Passbook / Statement')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Bank Passbook / Statement
            </h2>
            <p class="text-sm text-gray-500 mt-1">View all transactions in chronological order with running balance</p>
        </div>

        <form method="GET" action="{{ route('reconciliation.passbook') }}">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Bank
                    </label>
                    <select name="bank_id"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Transaction
                        Type</label>
                    <select name="transaction_type"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="all" {{ ($filters['transaction_type'] ?? 'all') == 'all' ? 'selected' : '' }}>All Types
                        </option>
                        <option value="deposit" {{ ($filters['transaction_type'] ?? '') == 'deposit' ? 'selected' : '' }}>
                            Deposits Only</option>
                        <option value="withdrawal" {{ ($filters['transaction_type'] ?? '') == 'withdrawal' ? 'selected' : '' }}>Withdrawals Only</option>
                        <option value="settlement" {{ ($filters['transaction_type'] ?? '') == 'settlement' ? 'selected' : '' }}>Settlements Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Start Date
                    </label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        End Date
                    </label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        Sort Order
                    </label>
                    <select name="sort_order"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="asc" {{ ($filters['sort_order'] ?? 'asc') == 'asc' ? 'selected' : '' }}>Oldest First
                            (Ascending)</option>
                        <option value="desc" {{ ($filters['sort_order'] ?? '') == 'desc' ? 'selected' : '' }}>Newest First
                            (Descending)</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Apply Filters
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('reconciliation.passbook') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-all">
                    Clear Filters
                </a>
                <a href="{{ route('reconciliation.passbook.export', request()->query()) }}"
                    class="px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-sm hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-blue-500">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Opening Balance</p>
            <p class="text-2xl font-bold text-blue-600 currency">₹{{ number_format($passbookData['opening_balance'], 2) }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-emerald-500">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Credit</p>
            <p class="text-2xl font-bold text-emerald-600 currency">₹{{ number_format($passbookData['total_credit'], 2) }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-red-500">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Debit</p>
            <p class="text-2xl font-bold text-red-600 currency">₹{{ number_format($passbookData['total_debit'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-indigo-500">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Closing Balance</p>
            <p class="text-2xl font-bold text-indigo-600 currency">₹{{ number_format($passbookData['closing_balance'], 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">UTR</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Debit (₹)
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Credit (₹)
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Balance
                            (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($passbookData['transactions'] as $transaction)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction['type'] == 'deposit')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        Deposit
                                    </span>
                                @elseif($transaction['type'] == 'withdrawal')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-50 text-red-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                        Withdrawal
                                    </span>
                                @elseif(in_array($transaction['type'], ['settlement_in', 'settlement_out', 'settlement']))
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                        {{ $transaction['type'] == 'settlement_in' ? 'Settlement IN' : ($transaction['type'] == 'settlement_out' ? 'Settlement OUT' : 'Settlement') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction['description'] }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $transaction['utr'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($transaction['amount'] < 0)
                                    <span
                                        class="font-semibold text-red-600 currency">{{ number_format(abs($transaction['amount']), 2) }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($transaction['amount'] > 0)
                                    <span
                                        class="font-semibold text-emerald-600 currency">{{ number_format($transaction['amount'], 2) }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-bold text-indigo-600 text-lg currency">{{ number_format($transaction['running_balance'], 2) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
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

    @if($passbookData['transactions']->total() > 0)
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="pagination-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Showing {{ $passbookData['transactions']->firstItem() ?? 0 }} -
                    {{ $passbookData['transactions']->lastItem() ?? 0 }} of {{ $passbookData['transaction_count'] }}
                    transactions</span>
            </div>

            @if($passbookData['transactions']->hasPages())
                <div>
                    {{ $passbookData['transactions']->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection