@extends('layouts.app')

@section('title', 'Smart Upload')

@section('content')
    @if(!isset($report))
        {{-- Upload Form Section --}}
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <div class="text-center mb-8">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Smart Upload</h2>
                    <p class="text-gray-600">Upload ONE Excel file containing all transaction types - we'll handle the rest
                        automatically</p>
                </div>

                <div class="flex gap-3 mb-8 justify-center flex-wrap">
                    <a href="{{ route('reconciliation.samples.smart') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-semibold text-sm hover:bg-emerald-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Basic Sample (10 rows)
                    </a>
                    <a href="{{ route('reconciliation.samples.comprehensive') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl font-semibold text-sm hover:bg-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Comprehensive Sample (90 rows)
                    </a>
                </div>

                <form method="POST" action="{{ route('reconciliation.upload.process') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Excel File</label>
                        <div class="relative">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-indigo-600 file:to-purple-600 file:text-white hover:file:shadow-lg file:transition-all cursor-pointer border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-indigo-400 transition-all">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Supported formats: .xlsx, .xls, .csv (Max: 10MB)</p>
                    </div>

                    <button type="submit"
                        class="w-full px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-base hover:shadow-2xl hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload & Process File
                    </button>
                </form>
            </div>

            {{-- How It Works --}}
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 mb-6 border border-indigo-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    How It Works
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Deposits</p>
                            <p class="text-xs text-gray-600">Rows with bank_name + amount (no status or from_bank)</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Withdrawals</p>
                            <p class="text-xs text-gray-600">Rows with bank_name + amount + status (pending/completed)</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Settlements</p>
                            <p class="text-xs text-gray-600">Rows with from_bank + to_bank + amount</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Closings</p>
                            <p class="text-xs text-gray-600">Rows with bank_name + date + actual_closing</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-white/60 rounded-lg border border-indigo-200">
                    <p class="text-xs text-indigo-800 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Your Excel file can contain mixed row types. The system will automatically detect and process each
                            row correctly.</span>
                    </p>
                </div>
            </div>

            {{-- Required Headers Table --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Required Excel Headers
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Header
                                    Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Required For</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Format
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">bank_name</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Deposits, Withdrawals, Closings</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">date</code></td>
                                <td class="px-6 py-3 text-sm text-gray-600">All types</td>
                                <td class="px-6 py-3 text-sm text-gray-500">YYYY-MM-DD or Excel date</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">amount</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Deposits, Withdrawals, Settlements</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Number (positive)</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">utr</code></td>
                                <td class="px-6 py-3 text-sm text-gray-600">Optional (all types)</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">status</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Withdrawals only</td>
                                <td class="px-6 py-3 text-sm text-gray-500">pending or completed</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">from_bank</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Settlements only</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">to_bank</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Settlements only</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">actual_closing</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Closings only</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Number</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">source_name</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Optional</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3"><code
                                        class="px-2 py-1 bg-gray-100 text-indigo-600 rounded text-xs font-mono">remark</code>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">Optional</td>
                                <td class="px-6 py-3 text-sm text-gray-500">Text</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        {{-- Upload Report --}}
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-emerald-600 mb-2">Upload Successful!</h2>
                    <p class="text-gray-600">Your file has been processed successfully</p>
                </div>

                {{-- Summary Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border-l-4 border-gray-400">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Rows</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $report['total_rows'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border-l-4 border-emerald-500">
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-1">Deposits</p>
                        <p class="text-2xl font-bold text-emerald-700">{{ $report['deposits_inserted'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-4 border-l-4 border-red-500">
                        <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-1">Withdrawals</p>
                        <p class="text-2xl font-bold text-red-700">{{ $report['withdrawals_inserted'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border-l-4 border-blue-500">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Settlements</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $report['settlements_inserted'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border-l-4 border-purple-500">
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">Closings</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $report['closings_updated'] }}</p>
                    </div>
                </div>

                @if($report['failed_rows'] > 0)
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="font-bold text-red-800">{{ $report['failed_rows'] }} rows failed to process</p>
                        </div>
                    </div>
                @endif

                {{-- Processing Summary --}}
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Processing Summary
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="text-sm font-medium text-gray-700">✓ Deposits Inserted</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $report['deposits_inserted'] }} rows</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="text-sm font-medium text-gray-700">✓ Withdrawals Inserted</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $report['withdrawals_inserted'] }} rows</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="text-sm font-medium text-gray-700">✓ Settlements Inserted</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $report['settlements_inserted'] }} rows</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="text-sm font-medium text-gray-700">✓ Closings Updated</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $report['closings_updated'] }} rows</span>
                        </div>
                    </div>
                </div>

                {{-- Banks Created --}}
                @if(count($report['banks_created']) > 0)
                    <div class="bg-blue-50 rounded-xl p-6 mb-8 border border-blue-200">
                        <h3 class="text-lg font-bold text-blue-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Banks Auto-Created
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report['banks_created'] as $bankName)
                                <span
                                    class="px-3 py-1.5 bg-white text-blue-700 rounded-lg text-sm font-medium border border-blue-200">{{ $bankName }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Errors --}}
                @if(count($report['errors']) > 0)
                    <div class="bg-red-50 rounded-xl p-6 mb-8 border border-red-200">
                        <h3 class="text-lg font-bold text-red-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Errors ({{ count($report['errors']) }})
                        </h3>
                        <ul class="space-y-2">
                            @foreach($report['errors'] as $error)
                                <li class="text-sm text-red-800 flex items-start gap-2">
                                    <span class="text-red-500 mt-0.5">•</span>
                                    <span>{{ $error }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3 justify-center pt-6 border-t border-gray-200">
                    <a href="{{ route('reconciliation.index') }}"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                        View Dashboard
                    </a>
                    <a href="{{ route('reconciliation.deposits') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                        View Deposits
                    </a>
                    <a href="{{ route('reconciliation.withdrawals') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                        View Withdrawals
                    </a>
                    <a href="{{ route('reconciliation.settlements') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                        View Settlements
                    </a>
                    <a href="{{ route('reconciliation.upload') }}"
                        class="px-6 py-3 bg-emerald-500 text-white rounded-xl font-semibold hover:bg-emerald-600 transition-all">
                        Upload Another File
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection