<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passbook - Global Log</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --accent: #6366f1;
            --accent2: #a855f7;
            --border-glass: rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-deep);
            color: #f8fafc;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(168, 85, 247, 0.15) 0%, transparent 40%);
        }
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
        }
        .passbook-table th {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.7rem;
            color: #94a3b8;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }
        .passbook-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('calculation.index') }}" class="text-accent hover:text-white transition-colors text-sm flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-4xl font-bold tracking-tight text-white">Daily <span class="text-accent2">Passbook</span></h1>
                <p class="text-slate-400 mt-1">Global transaction log for all customers</p>
            </div>

            <!-- Filters -->
            <form id="master-filter-form" method="GET" action="{{ route('calculation.master_log') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center bg-white/5 border border-white/10 rounded-xl overflow-hidden">
                    <a href="{{ route('calculation.master_log', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString(), 'search' => $search, 'type' => $type]) }}" 
                       class="p-2.5 hover:bg-white/10 transition-colors border-r border-white/10" title="Previous Day">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="relative">
                        <input type="date" name="date" value="{{ $date }}" 
                            onchange="this.form.submit()"
                            class="bg-transparent px-4 py-2.5 text-white outline-none cursor-pointer">
                    </div>
                    <a href="{{ route('calculation.master_log', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString(), 'search' => $search, 'type' => $type]) }}" 
                       class="p-2.5 hover:bg-white/10 transition-colors border-l border-white/10" title="Next Day">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="relative group">
                    <select name="type" onchange="this.form.submit()"
                        class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all cursor-pointer appearance-none pr-10">
                        <option value="" class="bg-slate-900">All Transactions</option>
                        <option value="credit" {{ $type == 'credit' ? 'selected' : '' }} class="bg-slate-900">Only Credits (In)</option>
                        <option value="debit" {{ $type == 'debit' ? 'selected' : '' }} class="bg-slate-900">Only Debits (Out)</option>
                    </select>
                    <div class="absolute -top-2.5 left-3 px-2 bg-slate-900 text-[10px] font-bold text-accent uppercase tracking-widest">Type</div>
                    <div class="absolute right-3 top-3.5 pointer-events-none text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div class="relative group">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Amt, Name, Remarks..." 
                        class="bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white focus:border-accent2 focus:ring-1 focus:ring-accent2 outline-none transition-all w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="submit" class="hidden">Search</button>
                    <div class="absolute -top-2.5 left-3 px-2 bg-slate-900 text-[10px] font-bold text-accent2 uppercase tracking-widest">Search</div>
                </div>

                <a href="{{ route('calculation.master_log.export', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 font-bold hover:bg-emerald-500/20 transition-all uppercase text-xs tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
            </form>
        </header>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                <div class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Total Daily Cash-In</div>
                <div class="text-2xl font-bold text-white">₹{{ number_format($dailyCredit, 2) }}</div>
            </div>
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all"></div>
                <div class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">Total Daily Cash-Out</div>
                <div class="text-2xl font-bold text-white">₹{{ number_format($dailyDebit, 2) }}</div>
            </div>
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-accent/10 rounded-full blur-2xl group-hover:bg-accent/20 transition-all"></div>
                <div class="text-[10px] font-bold text-accent uppercase tracking-widest mb-1">Daily Net Balance</div>
                <div class="text-2xl font-bold text-white">₹{{ number_format($dailyNet, 2) }}</div>
            </div>
        </div>

        <!-- Passbook Table -->
        <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full passbook-table border-collapse">
                    <thead>
                        <tr class="text-left">
                            <th>Sr. No.</th>
                            <th>Customer Details</th>
                            <th>Description/Remarks</th>
                            <th class="text-right">Credit (In)</th>
                            <th class="text-right">Debit (Out)</th>
                            <th class="text-right">Running Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $runningTotal = 0; @endphp
                        @forelse($transactions as $index => $txn)
                            @php 
                                if($txn->transaction_type == 'credit') $runningTotal += $txn->amount;
                                else $runningTotal -= $txn->amount;
                            @endphp
                            <tr class="hover:bg-white/2 transition-colors">
                                <td class="text-slate-500 font-mono text-xs">{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-semibold text-white">{{ $txn->customer->customer_name ?? 'Unknown' }}</div>
                                    <div class="text-[10px] text-accent font-bold uppercase tracking-wider">{{ $txn->customer_id }}</div>
                                </td>
                                <td>
                                    <div class="text-slate-300">{{ $txn->remarks ?: '--' }}</div>
                                    <div class="text-[10px] text-slate-500 flex items-center gap-1 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $txn->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    @if($txn->transaction_type == 'credit')
                                        <span class="text-emerald-400 font-bold">₹{{ number_format($txn->amount, 2) }}</span>
                                    @else
                                        <span class="text-slate-700">--</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($txn->transaction_type == 'debit')
                                        <span class="text-rose-400 font-bold">₹{{ number_format($txn->amount, 2) }}</span>
                                    @else
                                        <span class="text-slate-700">--</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <span class="font-mono {{ $runningTotal >= 0 ? 'text-blue-400' : 'text-orange-400' }}">
                                        ₹{{ number_format($runningTotal, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400">No transactions recorded for this day.</p>
                                        @if($search)
                                            <a href="{{ route('calculation.master_log', ['date' => $date]) }}" class="text-accent text-sm mt-2 hover:underline">Clear search filters</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transactions->isNotEmpty())
                    <tfoot class="bg-white/5">
                        <tr>
                            <td colspan="3" class="text-right font-bold uppercase tracking-widest text-xs text-slate-400">Daily Totals</td>
                            <td class="text-right font-bold text-emerald-400">₹{{ number_format($dailyCredit, 2) }}</td>
                            <td class="text-right font-bold text-rose-400">₹{{ number_format($dailyDebit, 2) }}</td>
                            <td class="text-right font-bold text-blue-400">₹{{ number_format($runningTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            @if($transactions->hasPages())
                {{ $transactions->links('vendor.pagination.tailwind') }}
            @endif
        </div>

        <footer class="text-center py-8">
            <p class="text-[10px] text-slate-600 uppercase tracking-[0.3em] font-bold">Master Passbook System • Professional Ledger Tool</p>
        </footer>
    </div>
</body>
</html>
