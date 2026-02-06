@extends('layouts.app')

@section('title', 'Reconciliation Dashboard')

@section('content')
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        @php
            $totalPayIn = collect($reconciliationData)->sum('total_deposits');
            $totalPayOut = collect($reconciliationData)->sum('total_withdrawals');
            $totalPending = collect($reconciliationData)->sum('pending_withdrawals');
            $totalSystemBalance = collect($reconciliationData)->sum('system_balance');
            $totalDifference = collect($reconciliationData)->sum('difference');
            $totalActualClosing = collect($reconciliationData)->sum('actual_closing');
        @endphp

        <div class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 border-emerald-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl gradient-green flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pay IN</p>
                    <p class="text-2xl font-bold text-gray-900 currency">₹{{ number_format($totalPayIn, 0) }}</p>
                </div>
            </div>
        </div>

        <div class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 border-red-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl gradient-red flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pay OUT</p>
                    <p class="text-2xl font-bold text-gray-900 currency">₹{{ number_format($totalPayOut, 0) }}</p>
                </div>
            </div>
        </div>

        <div class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 border-amber-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl gradient-amber flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 currency">₹{{ number_format($totalPending, 0) }}</p>
                </div>
            </div>
        </div>

        <div class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl gradient-blue flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">System</p>
                    <p class="text-2xl font-bold text-gray-900 currency">₹{{ number_format($totalSystemBalance, 0) }}</p>
                </div>
            </div>
        </div>

        <div class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 border-indigo-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl gradient-accent flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Actual</p>
                    <p class="text-2xl font-bold text-gray-900 currency">₹{{ number_format($totalActualClosing, 0) }}</p>
                </div>
            </div>
        </div>

        <div
            class="kpi-card bg-white rounded-2xl p-5 shadow-lg border-l-4 {{ abs($totalDifference) > 0.01 ? 'border-red-500' : 'border-emerald-500' }}">
            <div class="flex items-center gap-3 mb-2">
                <div
                    class="w-10 h-10 rounded-xl {{ abs($totalDifference) > 0.01 ? 'gradient-red' : 'gradient-green' }} flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Difference</p>
                    <p
                        class="text-2xl font-bold {{ abs($totalDifference) > 0.01 ? 'text-red-600' : 'text-emerald-600' }} currency">
                        ₹{{ number_format($totalDifference, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <form method="GET" action="{{ route('reconciliation.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
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

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Apply
                    </button>
                    <a href="{{ route('reconciliation.index') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-all">
                        Clear
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="text-sm text-gray-500">
                    <span class="font-medium text-gray-700">{{ count($reconciliationData) }}</span> banks showing
                </div>
                <a href="{{ route('reconciliation.export', request()->query()) }}"
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

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Bank</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pay IN
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pay OUT
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Net
                            Settlements</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">System
                            Balance</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actual
                            Closing</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Difference
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Pending
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reconciliationData as $row)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $row['bank_name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($row['bank_type']) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-semibold text-emerald-600 currency">₹{{ number_format($row['total_deposits'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-semibold text-red-600 currency">₹{{ number_format($row['total_withdrawals'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $row['net_settlements'] >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    @if($row['net_settlements'] >= 0)
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    @endif
                                    ₹{{ number_format(abs($row['net_settlements']), 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-bold text-blue-600 text-base currency">₹{{ number_format($row['system_balance'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-bold text-gray-900 text-base currency">₹{{ number_format($row['actual_closing'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ abs($row['difference']) > 0.01 ? 'bg-red-100 text-red-700 ring-2 ring-red-200' : 'bg-emerald-100 text-emerald-700' }}">
                                    @if(abs($row['difference']) > 0.01)
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @endif
                                    ₹{{ number_format($row['difference'], 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    ₹{{ number_format($row['pending_withdrawals'], 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">No reconciliation data available</p>
                                        <p class="text-gray-400 text-sm mt-1">Upload transactions to get started</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($reconciliationData) > 0)
        <div x-data="{ open: false }" class="mt-6">
            <button @click="open = !open"
                class="flex items-center gap-2 text-sm font-medium text-indigo-200 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                How reconciliation works
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="mt-4 bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                <div class="grid md:grid-cols-2 gap-4 text-sm text-indigo-100">
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-2"></div>
                        <div><strong class="text-white">Pay IN:</strong> Total deposits received</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-400 mt-2"></div>
                        <div><strong class="text-white">Pay OUT:</strong> Total completed withdrawals</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-2"></div>
                        <div><strong class="text-white">Net Settlements:</strong> Settlements IN minus Settlements OUT</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-2"></div>
                        <div><strong class="text-white">System Balance:</strong> Pay IN - Pay OUT + Net Settlements</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-purple-400 mt-2"></div>
                        <div><strong class="text-white">Difference:</strong> Actual Closing - System Balance</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-2"></div>
                        <div><strong class="text-white">Pending:</strong> Pending withdrawals (not in system balance)</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection