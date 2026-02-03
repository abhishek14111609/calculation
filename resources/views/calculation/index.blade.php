<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculation Control Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'system-ui', 'sans-serif'] },
                    colors: {
                        ink: '#e5edff',
                        muted: '#94a3b8',
                        navy: '#0b1220',
                        navyDeep: '#0a0f1d',
                        glass: 'rgba(255,255,255,0.06)',
                        borderGlass: 'rgba(255,255,255,0.12)',
                        accent: '#7c3aed',
                        accent2: '#22d3ee',
                    },
                    boxShadow: {
                        soft: '0 18px 44px rgba(0,0,0,0.28)',
                        card: '0 12px 32px rgba(8, 15, 40, 0.35)',
                    },
                    borderRadius: {
                        xl2: '16px',
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background: radial-gradient(circle at 12% 18%, rgba(124, 58, 237, 0.25), transparent 28%),
                        radial-gradient(circle at 88% 8%, rgba(34, 211, 238, 0.22), transparent 30%),
                        linear-gradient(160deg, #0b1220 0%, #0a0f1d 45%, #0a0f1d 100%);
        }

        .backdrop-glass { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .drop-overlay { pointer-events: none; opacity: 0; transition: opacity 150ms ease; }
        .dropzone.is-dragging { border-color: rgba(124, 58, 237, 0.7); box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.18); }
        .dropzone.is-dragging .drop-overlay { opacity: 1; }
    </style>
</head>
<body class="font-sans text-ink min-h-screen py-6">
@php $queryString = http_build_query(request()->query()); @endphp
<div class="w-full px-4 sm:px-6 lg:px-10 space-y-5">
    <header class="flex flex-wrap items-center justify-between gap-3 bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass px-4 sm:px-5 py-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent to-accent2 text-white flex items-center justify-center font-bold tracking-tight">CL</div>
            <div>
                <div class="text-lg font-bold tracking-tight text-white">Calculation Control Center</div>
                <div class="text-sm text-muted">Balances • Imports • Exports</div>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('calculation.export.csv') . ($queryString ? '?' . $queryString : '') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-cyan-400/50 bg-white/10 text-white font-semibold shadow-sm hover:-translate-y-0.5 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h11v-2H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h9V4H4Zm13.586 0a2 2 0 0 1 1.414.586l3.414 3.414A2 2 0 0 1 23 9.414V20a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2.586ZM15 6v14h6V9.414L17.586 6H15Zm2 9h2v3h-2v-3Zm0-7h2v5h-2V8Z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('calculation.export.excel') . ($queryString ? '?' . $queryString : '') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M5 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a1 1 0 0 0 1-1V8.414a1 1 0 0 0-.293-.707l-5.414-5.414A1 1 0 0 0 13.586 2H5Zm0 2h8v4a1 1 0 0 0 1 1h4v10H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm9.707 9.707-1.414-1.414L11 14.586 8.707 12.293l-1.414 1.414L9.586 16 7.293 18.293l1.414 1.414L11 17.414l2.293 2.293 1.414-1.414L12.414 16l2.293-2.293Z"/></svg>
                Export Excel
            </a>
        </div>
    </header>
    {{-- Hero removed for a tighter, dashboard-first layout --}}

    @foreach (['success', 'warning', 'error'] as $type)
        @if (session($type))
            <div class="rounded-xl border border-cyan-400/40 bg-cyan-50/60 text-slate-900 shadow-soft px-4 py-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 flex items-center justify-center text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            @if($type === 'success')
                                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm5.707 7.293-6.364 6.364a1 1 0 0 1-1.414 0l-3.293-3.293 1.414-1.414 2.586 2.586 5.657-5.657 1.414 1.414Z"/>
                            @elseif($type === 'warning')
                                <path d="M10.29 3.859c.78-1.358 2.64-1.358 3.42 0l7.518 13.106C22.993 18.323 21.98 20 20.28 20H3.72c-1.7 0-2.713-1.677-1.948-3.035L9.29 3.86ZM12 8a1 1 0 0 0-1 1v4h2V9a1 1 0 0 0-1-1Zm0 8a1.25 1.25 0 1 0 0-2.5A1.25 1.25 0 0 0 12 16Z"/>
                            @else
                                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm-1 5h2v6h-2V7Zm0 8h2v2h-2v-2Z"/>
                            @endif
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold capitalize">{{ $type }}</div>
                        <div class="text-slate-700">{{ session($type) }}</div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Customers</span>
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 text-accent flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M7 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm10 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm0 2c3.314 0 6 2.239 6 5v2h-4v-2c0-1.18-.837-2.191-2-2.45V13Zm-10 0c3.314 0 6 2.239 6 5v2H1v-2c0-2.761 2.686-5 6-5Z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-white">{{ number_format($customers->count()) }}</div>
        </div>
        <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Credit</span>
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-200/40 to-emerald-300/40 text-emerald-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M13 5V2h-2v3H8v2h3v3h2V7h3V5h-3Z"/><path d="M3 13a7 7 0 1 1 14 0 7 7 0 0 1-14 0Zm7-9a9 9 0 1 0 9 9 9 9 0 0 0-9-9Z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-emerald-200">₹{{ number_format($grandTotalCredit, 2) }}</div>
        </div>
        <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Debit</span>
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-200/40 to-red-300/40 text-red-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M7 11h10v2H7v-2Z"/><path d="M3 13a7 7 0 1 1 14 0 7 7 0 0 1-14 0Zm7-9a9 9 0 1 0 9 9 9 9 0 0 0-9-9Z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-red-200">₹{{ number_format($grandTotalDebit, 2) }}</div>
        </div>
        <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Final Balance</span>
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 text-accent flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M11 6h2v3h2v2h-2v3h2v2h-2v2h-2v-2H9v-2h2v-3H9v-2h2V6Zm-7 8a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm8-10a10 10 0 1 0 10 10A10 10 0 0 0 12 4Z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold tracking-tight text-white">₹{{ number_format($grandFinalBalance, 2) }}</div>
        </div>
    </section>

    <section class="grid lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 space-y-4">
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-muted">Upload center</div>
                <div class="text-muted text-sm">Drag & drop · Auto-validate</div>
            </div>

            <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-accent/20 to-accent2/20 text-accent flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2.05a1 1 0 0 0-2 0V5H8a3 3 0 0 0-3 3v7.764a3 3 0 0 0 1.337 2.5l4 2.667a3 3 0 0 0 3.326 0l4-2.666A3 3 0 0 0 19 15.765V8a3 3 0 0 0-3-3h-3V2.05ZM8 8h8a1 1 0 0 1 1 1v6.765a1 1 0 0 1-.446.832l-4 2.666a1 1 0 0 1-1.108 0l-4-2.666A1 1 0 0 1 7 15.765V9a1 1 0 0 1 1-1Z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-white">Upload Customers</h3>
                        <p class="text-sm text-muted">CSV/XLSX · up to 10MB · Columns: customer_id, customer_name, mobile, email, opening_balance.</p>
                    </div>
                </div>
                <form id="customers-form" action="{{ route('calculation.upload.customers') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <label class="dropzone block relative border border-dashed border-borderGlass rounded-xl bg-white/5 p-4 cursor-pointer transition hover:-translate-y-0.5 hover:border-accent/70 hover:shadow-card">
                        <input id="customer_file" type="file" name="customer_file" accept=".csv,.xlsx,.xls" required aria-label="Upload customers file" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="drop-overlay absolute inset-0 rounded-xl bg-gradient-to-r from-accent/10 to-accent2/10"></div>
                        <div class="relative flex items-center justify-between gap-3 flex-wrap">
                            <div>
                                <div class="font-semibold text-white">Drop file or click to upload</div>
                                <div class="text-sm text-muted">csv, xlsx · up to 10MB · Extra columns ignored</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-2 rounded-lg border border-cyan-400/60 bg-white/20 text-white font-semibold">Choose file</span>
                        </div>
                    </label>
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <small id="customer_file_name" class="text-muted">No file selected</small>
                        <div class="flex gap-3">
                            <a href="#" class="text-sm text-accent2 hover:underline">Download sample</a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition">
                                Upload customers
                            </button>
                        </div>
                    </div>
                    <div class="w-full bg-white/10 rounded-lg h-2 overflow-hidden">
                        <div id="customer_progress" class="h-full bg-gradient-to-r from-accent to-accent2 rounded-lg transition-all" style="width: 0%;"></div>
                    </div>
                </form>
            </div>

            <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-200/40 to-emerald-300/40 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M21 11.003v2H12a1 1 0 0 1-1-1v-9h2v8h8Z"/><path d="M10 5.834 6.707 9.127 3.414 5.834 2 7.248l4.707 4.707L11.414 7.25 10 5.834Z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-white">Upload Transactions</h3>
                        <p class="text-sm text-muted">CSV/XLSX · up to 10MB · Columns: transaction_id, customer_id, date, type, amount, remarks.</p>
                    </div>
                </div>
                <form id="transactions-form" action="{{ route('calculation.upload.transactions') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <label class="dropzone block relative border border-dashed border-borderGlass rounded-xl bg-white/5 p-4 cursor-pointer transition hover:-translate-y-0.5 hover:border-emerald-400/70 hover:shadow-card">
                        <input id="transaction_file" type="file" name="transaction_file" accept=".csv,.xlsx,.xls" required aria-label="Upload transactions file" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="drop-overlay absolute inset-0 rounded-xl bg-gradient-to-r from-emerald-200/20 to-cyan-200/20"></div>
                        <div class="relative flex items-center justify-between gap-3 flex-wrap">
                            <div>
                                <div class="font-semibold text-white">Drop file or click to upload</div>
                                <div class="text-sm text-muted">Auto-matches to customers</div>
                            </div>
                            <span class="inline-flex items-center px-3 py-2 rounded-lg border border-cyan-400/60 bg-white/20 text-white font-semibold">Choose file</span>
                        </div>
                    </label>
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <small id="transaction_file_name" class="text-muted">No file selected</small>
                        <div class="flex gap-3">
                            <a href="#" class="text-sm text-accent2 hover:underline">Download sample</a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition">
                                Upload transactions
                            </button>
                        </div>
                    </div>
                    <div class="w-full bg-white/10 rounded-lg h-2 overflow-hidden">
                        <div id="transaction_progress" class="h-full bg-gradient-to-r from-emerald-400 to-cyan-400 rounded-lg transition-all" style="width: 0%;"></div>
                    </div>
                </form>
            </div>

            {{-- <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-4 space-y-3">

                {{-- <div class="flex flex-wrap gap-2">
                    <a href="{{ route('calculation.export.excel') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition" title="Download full Excel export">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M5 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a1 1 0 0 0 1-1V8.414a1 1 0 0 0-.293-.707l-5.414-5.414A1 1 0 0 0 13.586 2H5Zm0 2h8v4a1 1 0 0 0 1 1h4v10H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm9.707 9.707-1.414-1.414L11 14.586 8.707 12.293l-1.414 1.414L9.586 16 7.293 18.293l1.414 1.414L11 17.414l2.293 2.293 1.414-1.414L12.414 16l2.293-2.293Z"/></svg>
                        Export Excel
                    </a>
                    <a href="{{ route('calculation.export.csv') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-cyan-400/60 bg-white/20 text-white font-semibold shadow-sm hover:-translate-y-0.5 transition" title="Download full CSV export">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h11v-2H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h9V4H4Zm13.586 0a2 2 0 0 1 1.414.586l3.414 3.414A2 2 0 0 1 23 9.414V20a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2.586ZM15 6v14h6V9.414L17.586 6H15Zm2 9h2v3h-2v-3Zm0-7h2v5h-2V8Z"/></svg>
                        Export CSV
                    </a>
                </div> --}}
                {{-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-muted">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Recalc after each upload
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-accent2"></span> Sticky header ledger
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-accent"></span> Download rules PDF
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-white/70"></span> Contact support
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="lg:col-span-8 space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-muted">Customer ledger</div>
                <div class="text-muted text-sm">Sticky header · Zebra rows · Actions</div>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-2 bg-white/90 text-slate-900 rounded-xl2 border border-white/20 shadow-card p-3">
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Date from
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                </label>
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Date to
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                </label>
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Customer / mobile / email
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                </label>
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Type
                    <select name="type" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                        <option value="" @selected(!request('type'))>All</option>
                        <option value="credit" @selected(request('type')==='credit')>Credit only</option>
                        <option value="debit" @selected(request('type')==='debit')>Debit only</option>
                    </select>
                </label>
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Amount min
                    <input type="number" step="0.01" name="amount_min" value="{{ request('amount_min') }}" placeholder="Min" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                </label>
                <label class="flex flex-col gap-1 text-xs font-semibold tracking-wide text-slate-600">
                    Amount max
                    <input type="number" step="0.01" name="amount_max" value="{{ request('amount_max') }}" placeholder="Max" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                </label>
                <div class="md:col-span-2 lg:col-span-6 flex flex-wrap gap-2 justify-end pt-1">
                    <a href="{{ url()->current() }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">Clear</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-card hover:-translate-y-0.5 transition">Apply filters</button>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl2 border border-white/10 bg-white/85 text-slate-900 shadow-card">
                <div class="overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 sticky top-0 z-10 text-slate-600 uppercase tracking-[0.08em] text-xs">
                            <tr>
                                <th class="text-left px-5 py-3">Customer ID</th>
                                <th class="text-left px-5 py-3">Profile</th>
                                <th class="text-left px-5 py-3 whitespace-nowrap">Last Txn</th>
                                <th class="text-left px-5 py-3">Opening</th>
                                <th class="text-left px-5 py-3">Credit</th>
                                <th class="text-left px-5 py-3">Debit</th>
                                <th class="text-left px-5 py-3">Balance</th>
                                <th class="text-right px-5 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr class="border-t border-slate-200 odd:bg-white even:bg-slate-50 hover:bg-accent/5 transition-colors">
                                    <td class="px-5 py-3 font-semibold whitespace-nowrap">{{ $customer->customer_id }}</td>
                                    <td class="px-5 py-3">
                                        <div class="font-semibold">{{ $customer->customer_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $customer->mobile_number }}@if($customer->email) · {{ $customer->email }}@endif</div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">{{ optional($customer->last_txn_date)->format('Y-m-d') }}</td>
                                    <td class="px-5 py-3 whitespace-nowrap">₹{{ number_format($customer->opening_balance, 2) }}</td>
                                    <td class="px-5 py-3 whitespace-nowrap text-emerald-600">₹{{ number_format($customer->total_credit, 2) }}</td>
                                    <td class="px-5 py-3 whitespace-nowrap text-red-600">₹{{ number_format($customer->total_debit, 2) }}</td>
                                    <td class="px-5 py-3 font-semibold whitespace-nowrap">₹{{ number_format($customer->final_balance, 2) }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <form action="{{ route('calculation.delete.customer', $customer->customer_id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this customer and related transactions?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-10 h-10 inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition" aria-label="Delete customer {{ $customer->customer_name }}" title="Delete customer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 4V2h6v2h5v2H4V4h5Zm-3 4h12l-.8 11.2a2 2 0 0 1-1.995 1.8H8.795a2 2 0 0 1-1.995-1.8L6 8Zm4 2v8h2v-8h-2Z"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-6 text-center text-slate-500">No data yet. Upload customers and transactions to see results.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($customers, 'hasPages') && $customers->hasPages())
                <div class="flex items-center justify-between flex-wrap gap-3 text-slate-300 text-sm">
                    <div>Showing {{ $customers->firstItem() }}-{{ $customers->lastItem() }} of {{ $customers->total() }}</div>
                    <div class="text-slate-900">{{ $customers->onEachSide(1)->withQueryString()->links('pagination::tailwind') }}</div>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
    function bindDropzone(inputId, labelId, barId, zoneSelector) {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        const bar = document.getElementById(barId);
        const zone = input.closest(zoneSelector);

        const setFileName = file => {
            if (!file) {
                label.textContent = 'No file selected';
                bar.style.width = '0%';
                return;
            }
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            label.textContent = `${file.name} • ${sizeMb} MB`;
            bar.style.width = '12%';
            requestAnimationFrame(() => {
                setTimeout(() => { bar.style.width = '70%'; }, 120);
                setTimeout(() => { bar.style.width = '100%'; }, 360);
            });
        };

        input.addEventListener('change', e => setFileName(e.target.files[0]));

        ['dragenter','dragover'].forEach(evt => {
            input.addEventListener(evt, e => { e.preventDefault(); zone.classList.add('is-dragging'); });
        });
        ['dragleave','drop'].forEach(evt => {
            input.addEventListener(evt, e => { e.preventDefault(); zone.classList.remove('is-dragging'); });
        });
        input.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            if (file) { setFileName(file); }
        });
    }

    bindDropzone('customer_file', 'customer_file_name', 'customer_progress', '.dropzone');
    bindDropzone('transaction_file', 'transaction_file_name', 'transaction_progress', '.dropzone');
</script>
</body>
</html>
