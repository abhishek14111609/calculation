@extends('layouts.app')

@section('title', 'Bank Closings')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add/Update Closing Balance
        </h3>
        <form method="POST" action="{{ route('reconciliation.closings.update') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Bank *</label>
                    <select name="bank_id" required
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                        <option value="">Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Date *</label>
                    <input type="date" name="date" required
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Actual Closing
                        Balance *</label>
                    <input type="number" name="actual_closing" step="0.01" required placeholder="0.00"
                        class="w-full px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Save Closing
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
        <form method="GET" action="{{ route('reconciliation.closings') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
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

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Apply
                    </button>
                    <a href="{{ route('reconciliation.closings') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-all">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Bank</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actual
                            Closing</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Last
                            Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($closings as $closing)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $closing->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $closing->bank->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="font-bold text-indigo-600 text-base currency">₹{{ number_format($closing->actual_closing, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $closing->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 font-medium">No closing balances recorded</p>
                                        <p class="text-gray-400 text-sm mt-1">Add your first closing balance above</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($closings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $closings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection