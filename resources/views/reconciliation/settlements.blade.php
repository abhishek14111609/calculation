@extends('layouts.app')

@section('title', 'Settlements')

@section('content')
    <div class="bg-indigo-50/50 backdrop-blur-sm rounded-2xl p-4 mb-6 border border-indigo-200/50">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-indigo-800">
                <strong>Tip:</strong> Use the <a href="{{ route('reconciliation.upload') }}"
                    class="underline hover:text-indigo-600 transition-colors">Smart Upload</a> page to upload all
                transaction types at once.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <form method="GET" action="{{ route('reconciliation.settlements') }}">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">From Bank</label>
                    <select name="from_bank_id"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['from_bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">To Bank</label>
                    <select name="to_bank_id"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['to_bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Min Amount</label>
                    <input type="number" name="min_amount" step="0.01" value="{{ $filters['min_amount'] ?? '' }}"
                        placeholder="0.00"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Max Amount</label>
                    <input type="number" name="max_amount" step="0.01" value="{{ $filters['max_amount'] ?? '' }}"
                        placeholder="0.00"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Apply Filters
                    </button>
                    <a href="{{ route('reconciliation.settlements') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-all">
                        Clear
                    </a>
                </div>
                <a href="{{ route('reconciliation.settlements.export', request()->query()) }}"
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">From Bank
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider"></th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">To Bank
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">UTR</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Remark</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($settlements as $settlement)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $settlement->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $settlement->fromBank->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <svg class="w-6 h-6 text-indigo-400 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $settlement->toBank->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $settlement->utr ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-bold text-indigo-600 text-base currency">₹{{ number_format($settlement->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $settlement->remark ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">No settlements found</p>
                                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($settlements->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $settlements->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection