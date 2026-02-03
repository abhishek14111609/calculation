<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger - {{ $customer->customer_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .print-shadow-none { box-shadow: none !important; border: 1px solid #eee !important; }
        }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .ledger-table th, .ledger-table td { border: 1px solid #e2e8f0; padding: 10px 12px; }
        .ledger-table thead th { background-color: #f1f5f9; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    </style>
</head>
<body class="p-4 md:p-8">

    <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Header Actions -->
        <div class="flex items-center justify-between no-print">
            <a href="{{ route('calculation.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-accent transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"></path></svg>
                Back to Dashboard
            </a>
            <div class="flex gap-2">
                <a href="{{ route('calculation.ledger.export', ['customerId' => $customer->customer_id, 'from' => request('from'), 'to' => request('to')]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 border border-emerald-700 rounded-lg text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48V208a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V48A16,16,0,0,1,48,32H208A16,16,0,0,1,224,48ZM208,208V48H48V208H208Zm-96-88a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h24A8,8,0,0,1,112,120Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h24A8,8,0,0,1,112,152Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h24A8,8,0,0,1,112,184Zm64-64a8,8,0,0,1-8,8H144a8,8,0,0,1,0-16h24A8,8,0,0,1,176,120Zm0,32a8,8,0,0,1-8,8H144a8,8,0,0,1,0-16h24A8,8,0,0,1,176,152Zm0,32a8,8,0,0,1-8,8H144a8,8,0,0,1,0-16h24A8,8,0,0,1,176,184Z"></path></svg>
                    Export Excel
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M240,112H224V40a16,16,0,0,0-16-16H48A16,16,0,0,0,32,40v72H16a16,16,0,0,0-16,16v80a16,16,0,0,0,16,16H32v16a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V224h16a16,16,0,0,0,16-16V128A16,16,0,0,0,240,112ZM48,40H208V112H48ZM208,232H48V176H208Zm32-24H224V176a16,16,0,0,0-16-16H48a16,16,0,0,0-16,16v32H16V128H240v80Z"></path></svg>
                    Print Report
                </button>
            </div>
        </div>

        <!-- Statement Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden print-shadow-none">
            
            <!-- Report Header -->
            <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="space-y-1">
                        <h1 class="text-2xl font-bold text-slate-900">Statement of Account</h1>
                        <p class="text-slate-500 font-medium">{{ $customer->customer_name }}</p>
                        <div class="text-sm text-slate-500 space-y-0.5">
                            <div>ID: {{ $customer->customer_id }}</div>
                            @if($customer->mobile_number)<div>Mob: {{ $customer->mobile_number }}</div>@endif
                            @if($customer->address)<div>Address: {{ $customer->address }}</div>@endif
                        </div>
                    </div>
                    <div class="text-right space-y-1">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Period</div>
                        <div class="text-slate-900 font-semibold">
                            {{ $filters['from'] ? \Carbon\Carbon::parse($filters['from'])->format('d M Y') : 'Start' }} 
                            — 
                            {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}
                        </div>
                        <div class="mt-4">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Generated On</div>
                            <div class="text-slate-700 text-sm">{{ now()->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm ledger-table border-collapse">
                    <thead>
                        <tr>
                            <th class="text-center w-32">Date</th>
                            <th class="text-left">Vch Type</th>
                            <th class="text-left">Vch No.</th>
                            <th class="text-right w-36">Debit</th>
                            <th class="text-right w-36">Credit</th>
                            <th class="text-right w-40">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Opening Balance Row -->
                        <tr class="bg-slate-50/30 font-semibold text-slate-700">
                            <td class="text-center"></td>
                            <td class="text-left uppercase text-[10px] tracking-widest text-slate-500">Opening Balance</td>
                            <td class="text-left"></td>
                            <td class="text-right">
                                @if($openingBalance < 0) {{ number_format(abs($openingBalance), 2) }} @endif
                            </td>
                            <td class="text-right">
                                @if($openingBalance >= 0) {{ number_format($openingBalance, 2) }} @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                {{ number_format(abs($openingBalance), 2) }} 
                                <span class="text-[10px] font-bold ml-1">{{ $openingBalance >= 0 ? 'Cr' : 'Dr' }}</span>
                            </td>
                        </tr>

                        @php 
                            $runningBalance = $openingBalance;
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp

                        @foreach($transactions as $txn)
                            @php
                                if ($txn->transaction_type === 'credit') {
                                    $runningBalance += (float)$txn->amount;
                                    $totalCredit += (float)$txn->amount;
                                } else {
                                    $runningBalance -= (float)$txn->amount;
                                    $totalDebit += (float)$txn->amount;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="text-center text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($txn->transaction_date)->format('d M y') }}
                                </td>
                                <td class="text-left capitalize text-slate-700">
                                    {{ $txn->transaction_type === 'credit' ? 'Receipt' : 'Sales' }}
                                </td>
                                <td class="text-left text-slate-500 font-mono text-xs">
                                    {{ $txn->transaction_id }}
                                </td>
                                <td class="text-right text-red-600">
                                    {{ $txn->transaction_type === 'debit' ? number_format($txn->amount, 2) : '' }}
                                </td>
                                <td class="text-right text-emerald-600">
                                    {{ $txn->transaction_type === 'credit' ? number_format($txn->amount, 2) : '' }}
                                </td>
                                <td class="text-right font-medium whitespace-nowrap">
                                    {{ number_format(abs($runningBalance), 2) }}
                                    <span class="text-[10px] font-bold ml-1 {{ $runningBalance >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Total Row -->
                        <tr class="font-bold bg-slate-50/50 text-slate-800 border-t-2 border-slate-200">
                            <td colspan="3" class="text-right uppercase text-[10px] tracking-widest text-slate-500 p-4">Period Totals</td>
                            <td class="text-right">{{ number_format($totalDebit, 2) }}</td>
                            <td class="text-right">{{ number_format($totalCredit, 2) }}</td>
                            <td class="text-right"></td>
                        </tr>

                        <!-- Closing Balance Row -->
                        <tr class="bg-slate-900 text-white font-bold">
                            <td colspan="3" class="text-right uppercase text-[10px] tracking-widest text-slate-400 p-4">Closing Balance</td>
                            <td class="text-right"></td>
                            <td class="text-right">{{ number_format(abs($runningBalance), 2) }}</td>
                            <td class="text-right whitespace-nowrap">
                                {{ number_format(abs($runningBalance), 2) }}
                                <span class="text-[10px] font-bold ml-1 {{ $runningBalance >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Summary -->
            <div class="p-8 bg-slate-50/30">
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total Transactions:</span>
                            <span class="font-semibold text-slate-900">{{ $transactions->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Opening Balance:</span>
                            <span class="font-semibold {{ $openingBalance >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ number_format(abs($openingBalance), 2) }} {{ $openingBalance >= 0 ? 'Cr' : 'Dr' }}
                            </span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-200">
                            <span class="text-slate-900 font-bold uppercase tracking-wider text-xs">Net Closing Balance</span>
                            <span class="font-bold text-lg {{ $runningBalance >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                ₹{{ number_format(abs($runningBalance), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Footer -->
        <p class="text-center text-xs text-slate-400 py-4 no-print">
            Powered by CL Control Center • Automated Accounting System
        </p>
    </div>

</body>
</html>
