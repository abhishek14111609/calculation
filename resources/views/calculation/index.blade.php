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

        .backdrop-glass {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .drop-overlay {
            pointer-events: none;
            opacity: 0;
            transition: opacity 150ms ease;
        }

        .dropzone.is-dragging {
            border-color: rgba(124, 58, 237, 0.7);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.18);
        }

        .dropzone.is-dragging .drop-overlay {
            opacity: 1;
        }

        tr.is-selected {
            background-color: rgba(124, 58, 237, 0.08) !important;
        }

        /* Unique Upload Aesthetic */
        .unique-dropzone {
            background-color: #0d1117;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1.5px, transparent 1.5px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1.5px, transparent 1.5px);
            background-size: 30px 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .unique-dropzone::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, transparent 30%, #0d1117 90%);
            pointer-events: none;
        }

        .upload-icon-box {
            background: #161b22;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .unique-dropzone:hover .upload-icon-box {
            transform: scale(1.05);
            border-color: rgba(124, 58, 237, 0.5);
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.2);
        }

        .dashed-border {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 2px dashed rgba(66, 153, 225, 0.4);
            border-radius: 12px;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .unique-dropzone:hover .dashed-border {
            border-color: #22d3ee;
            transform: scale(1.1);
        }
    </style>
</head>

<body class="font-sans text-ink min-h-screen py-6">
    @php $queryString = http_build_query(request()->query()); @endphp
    <div class="w-full px-4 sm:px-6 lg:px-10 space-y-5">
        <header
            class="flex flex-wrap items-center justify-between gap-3 bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass px-4 sm:px-5 py-3">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent to-accent2 text-white flex items-center justify-center font-bold tracking-tight">
                    CL</div>
                <div>
                    <div class="text-lg font-bold tracking-tight text-white">Calculation Control Center</div>
                    <div class="text-sm text-muted">Balances • Imports • Exports</div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('calculation.master_log') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-accent2/40 bg-accent2/10 text-accent2 font-semibold shadow-sm hover:translate-x-1 hover:bg-accent2/20 transition-all border-dashed group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:rotate-12 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Passbook
                </a>
                <a href="{{ route('calculation.export.csv') . ($queryString ? '?' . $queryString : '') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-cyan-400/50 bg-white/10 text-white font-semibold shadow-sm hover:-translate-y-0.5 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M4 4a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h11v-2H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h9V4H4Zm13.586 0a2 2 0 0 1 1.414.586l3.414 3.414A2 2 0 0 1 23 9.414V20a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2.586ZM15 6v14h6V9.414L17.586 6H15Zm2 9h2v3h-2v-3Zm0-7h2v5h-2V8Z" />
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('calculation.export.excel') . ($queryString ? '?' . $queryString : '') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-lg hover:-translate-y-0.5 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M5 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a1 1 0 0 0 1-1V8.414a1 1 0 0 0-.293-.707l-5.414-5.414A1 1 0 0 0 13.586 2H5Zm0 2h8v4a1 1 0 0 0 1 1h4v10H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm9.707 9.707-1.414-1.414L11 14.586 8.707 12.293l-1.414 1.414L9.586 16 7.293 18.293l1.414 1.414L11 17.414l2.293 2.293 1.414-1.414L12.414 16l2.293-2.293Z" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </header>
        {{-- Hero removed for a tighter, dashboard-first layout --}}

        @foreach (['success', 'warning', 'error'] as $type)
            @if (session($type))
                <div class="rounded-xl border border-cyan-400/40 bg-cyan-50/60 text-slate-900 shadow-soft px-4 py-3">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                viewBox="0 0 24 24">
                                @if($type === 'success')
                                    <path
                                        d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm5.707 7.293-6.364 6.364a1 1 0 0 1-1.414 0l-3.293-3.293 1.414-1.414 2.586 2.586 5.657-5.657 1.414 1.414Z" />
                                @elseif($type === 'warning')
                                    <path
                                        d="M10.29 3.859c.78-1.358 2.64-1.358 3.42 0l7.518 13.106C22.993 18.323 21.98 20 20.28 20H3.72c-1.7 0-2.713-1.677-1.948-3.035L9.29 3.86ZM12 8a1 1 0 0 0-1 1v4h2V9a1 1 0 0 0-1-1Zm0 8a1.25 1.25 0 1 0 0-2.5A1.25 1.25 0 0 0 12 16Z" />
                                @else
                                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm-1 5h2v6h-2V7Zm0 8h2v2h-2v-2Z" />
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
                    <span
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 text-accent flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M7 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm10 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm0 2c3.314 0 6 2.239 6 5v2h-4v-2c0-1.18-.837-2.191-2-2.45V13Zm-10 0c3.314 0 6 2.239 6 5v2H1v-2c0-2.761 2.686-5 6-5Z" />
                        </svg>
                    </span>
                </div>
                <div class="text-2xl font-bold tracking-tight text-white">{{ number_format($customers->count()) }}</div>
            </div>
            <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-muted text-sm">Total Credit</span>
                    <span
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-200/40 to-emerald-300/40 text-emerald-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M13 5V2h-2v3H8v2h3v3h2V7h3V5h-3Z" />
                            <path d="M3 13a7 7 0 1 1 14 0 7 7 0 0 1-14 0Zm7-9a9 9 0 1 0 9 9 9 9 0 0 0-9-9Z" />
                        </svg>
                    </span>
                </div>
                <div class="text-2xl font-bold tracking-tight text-emerald-200">
                    ₹{{ number_format($grandTotalCredit, 2) }}</div>
            </div>
            <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-muted text-sm">Total Debit</span>
                    <span
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-200/40 to-red-300/40 text-red-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M7 11h10v2H7v-2Z" />
                            <path d="M3 13a7 7 0 1 1 14 0 7 7 0 0 1-14 0Zm7-9a9 9 0 1 0 9 9 9 9 0 0 0-9-9Z" />
                        </svg>
                    </span>
                </div>
                <div class="text-2xl font-bold tracking-tight text-red-200">₹{{ number_format($grandTotalDebit, 2) }}
                </div>
            </div>
            <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-3 sm:p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-muted text-sm">Final Balance</span>
                    <span
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent/15 to-accent2/15 text-accent flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M11 6h2v3h2v2h-2v3h2v2h-2v2h-2v-2H9v-2h2v-3H9v-2h2V6Zm-7 8a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm8-10a10 10 0 1 0 10 10A10 10 0 0 0 12 4Z" />
                        </svg>
                    </span>
                </div>
                <div class="text-2xl font-bold tracking-tight text-white">₹{{ number_format($grandFinalBalance, 2) }}
                </div>
            </div>
        </section>

        <section class="grid lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-muted">Upload center</div>
                    <div class="text-muted text-sm">Drag & drop · Auto-validate</div>
                </div>

                <div class="space-y-4">
                    <!-- Upload Customers -->
                    <form id="customers-form" action="{{ route('calculation.upload.customers') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="unique-dropzone rounded-2xl transition-all relative group">
                            <!-- Input is hidden but still reachable via label -->
                            <input id="customer_file" type="file" name="customer_file" accept=".csv,.xlsx,.xls" required
                                class="hidden">

                            <label for="customer_file"
                                class="flex flex-col items-center justify-center text-center p-8 min-h-[280px] cursor-pointer z-10 relative">
                                <div class="space-y-6">
                                    <h3 class="text-xl font-bold text-white tracking-tight">Upload Customers</h3>
                                    <p class="text-slate-400 max-w-[200px] mx-auto text-sm">Drag or drop your files here
                                        or click to upload</p>

                                    <div class="flex items-center justify-center py-4 relative">
                                        <div class="dashed-border"></div>
                                        <div
                                            class="upload-icon-box w-20 h-20 rounded-xl flex items-center justify-center text-cyan-400 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 16a4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div id="customer_file_name" class="text-xs font-medium text-cyan-400/80">CSV,
                                            XLSX · up to 10MB</div>
                                        <div class="flex items-center justify-center gap-4">
                                            <a href="{{ route('calculation.sample.customers') }}"
                                                class="text-[10px] text-slate-500 hover:text-cyan-400 transition-colors uppercase tracking-widest font-bold z-30 pointer-events-auto">Sample
                                                Excel</a>
                                            <button type="submit"
                                                class="relative z-30 px-6 py-2 bg-accent hover:bg-accent/80 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all shadow-lg hover:shadow-accent/20">
                                                Import Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Progress Bar -->
                            <div
                                class="absolute bottom-0 left-0 right-0 h-1.5 bg-white/5 rounded-b-2xl overflow-hidden">
                                <div id="customer_progress"
                                    class="h-full bg-gradient-to-r from-accent to-accent2 transition-all duration-500"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                    </form>

                    <!-- Upload Transactions -->
                    <form id="transactions-form" action="{{ route('calculation.upload.transactions') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="unique-dropzone rounded-2xl transition-all relative group">
                            <!-- Input is hidden but still reachable via label -->
                            <input id="transaction_file" type="file" name="transaction_file" accept=".csv,.xlsx,.xls"
                                required class="hidden">

                            <label for="transaction_file"
                                class="flex flex-col items-center justify-center text-center p-8 min-h-[280px] cursor-pointer z-10 relative">
                                <div class="space-y-6">
                                    <h3 class="text-xl font-bold text-white tracking-tight">Upload Transactions</h3>
                                    <p class="text-slate-400 max-w-[200px] mx-auto text-sm">Drag or drop your files here
                                        or click to upload</p>

                                    <div class="flex items-center justify-center py-4 relative">
                                        <div class="dashed-border" style="border-color: rgba(16, 185, 129, 0.4)"></div>
                                        <div
                                            class="upload-icon-box w-20 h-20 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div id="transaction_file_name" class="text-xs font-medium text-emerald-400/80">
                                            Auto-matches to customers</div>
                                        <div class="flex items-center justify-center gap-4">
                                            <a href="{{ route('calculation.sample.transactions') }}"
                                                class="text-[10px] text-slate-500 hover:text-emerald-400 transition-colors uppercase tracking-widest font-bold z-30 pointer-events-auto">Sample
                                                Excel</a>
                                            <button type="submit"
                                                class="relative z-30 px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all shadow-lg hover:shadow-emerald-500/20">
                                                Import Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Progress Bar -->
                            <div
                                class="absolute bottom-0 left-0 right-0 h-1.5 bg-white/5 rounded-b-2xl overflow-hidden">
                                <div id="transaction_progress"
                                    class="h-full bg-gradient-to-r from-emerald-500 to-cyan-400 transition-all duration-500"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white/10 border border-borderGlass rounded-xl2 shadow-card backdrop-glass p-4 space-y-3">

                    Export Excel
                    </a>
                    <a href="{{ route('calculation.export.csv') }}"
                        class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-cyan-400/60 bg-white/20 text-white font-semibold shadow-sm hover:-translate-y-0.5 transition"
                        title="Download full CSV export">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M4 4a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h11v-2H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h9V4H4Zm13.586 0a2 2 0 0 1 1.414.586l3.414 3.414A2 2 0 0 1 23 9.414V20a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2.586ZM15 6v14h6V9.414L17.586 6H15Zm2 9h2v3h-2v-3Zm0-7h2v5h-2V8Z" />
                        </svg>
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
            <div class="text-xs font-semibold uppercase tracking-[0.24em] text-muted">Customer list</div>
            <div class="text-muted text-sm">Select a customer to view detailed statement</div>
        </div>

        <form method="GET" action="{{ url()->current() }}"
            class="space-y-4 bg-white/90 text-slate-900 rounded-xl2 border border-white/20 shadow-card p-4">
            <div class="flex flex-wrap lg:flex-nowrap gap-3">
                <div class="flex-1 min-w-[300px] relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search by name, mobile or customer ID..."
                        class="w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60">
                    <svg class="absolute left-3 top-3 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex gap-2 min-w-fit">
                    <!-- Sort Filter -->
                    <div class="relative min-w-[140px]">
                        <select name="sort" onchange="this.form.submit()"
                            class="appearance-none w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60 text-slate-800 pr-10 font-medium">
                            <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Name
                                (A-Z)</option>
                            <option value="final_balance" {{ request('sort') == 'final_balance' || !request('sort') ? 'selected' : '' }}>Balance</option>
                            <option value="customer_id" {{ request('sort') == 'customer_id' ? 'selected' : '' }}>
                                Customer ID</option>
                            <option value="total_credit" {{ request('sort') == 'total_credit' ? 'selected' : '' }}>Total
                                Credit</option>
                            <option value="total_debit" {{ request('sort') == 'total_debit' ? 'selected' : '' }}>
                                Total Debit</option>
                        </select>
                        <svg class="absolute right-3 top-3.5 text-slate-400 pointer-events-none"
                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                            </path>
                        </svg>
                    </div>

                    <!-- Direction Filter -->
                    <div class="relative min-w-[140px]">
                        <select name="direction" onchange="this.form.submit()"
                            class="appearance-none w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60 text-slate-800 pr-10 font-medium">
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending
                            </option>
                            <option value="desc" {{ request('direction') == 'desc' || !request('direction') ? 'selected' : '' }}>Descending</option>
                        </select>
                        <svg class="absolute right-3 top-3.5 text-slate-400 pointer-events-none"
                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                            </path>
                        </svg>
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-purple-400/70 bg-gradient-to-r from-accent to-accent2 text-white font-semibold shadow-card hover:-translate-y-0.5 transition">Search</button>

                @if(request()->anyFilled(['q', 'sort', 'direction']))
                    <a href="{{ url()->current() }}"
                        class="inline-flex items-center justify-center p-2.5 rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition"
                        title="Reset Filters">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M165.66,101.66,139.31,128l26.35,26.34a8,8,0,0,1-11.32,11.32L128,139.31l-26.34,26.35a8,8,0,0,1-11.32-11.32L116.69,128,90.34,101.66a8,8,0,0,1,11.32-11.32L128,116.69l26.34-26.35a8,8,0,0,1,11.32,11.32ZM232,128A104,104,0,1,1,128,24,104.11,104.11,0,0,1,232,128Zm-16,0a88,88,0,1,0-88,88A88.1,88.1,0,0,0,216,128Z">
                            </path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <div id="bulk-actions-bar"
            class="hidden flex items-center justify-between gap-3 bg-red-500/10 border border-red-500/20 rounded-xl2 p-3 mb-3 backdrop-glass animate-in fade-in slide-in-from-top-2 duration-300">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold"
                    id="selected-count">0</div>
                <div>
                    <div class="text-sm font-bold text-white">Customers Selected</div>
                    <div class="text-xs text-red-200/70">Bulk actions will apply to these records</div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="confirmBulkDelete()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-red-700 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 256 256">
                        <path
                            d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z">
                        </path>
                    </svg>
                    Delete Selected
                </button>
                <button type="button" onclick="clearSelection()"
                    class="px-4 py-2 rounded-xl bg-white/10 text-white font-bold text-xs uppercase tracking-wider hover:bg-white/20 transition">
                    Cancel
                </button>
            </div>
        </div>

        <form id="bulk-delete-form" action="{{ route('calculation.bulk.delete.customers') }}" method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <div class="overflow-hidden rounded-xl2 border border-white/10 bg-white/85 text-slate-900 shadow-card">
            <div class="overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 sticky top-0 z-10 text-slate-600 uppercase tracking-[0.08em] text-xs">
                        <tr>
                            <th class="w-10 px-5 py-4">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent accent-accent cursor-pointer">
                            </th>
                            <th class="text-left px-5 py-4">Customer Details</th>
                            <th class="text-left px-5 py-4">Opening</th>
                            <th class="text-left px-5 py-4">Current Balance</th>
                            <th class="text-right px-5 py-4">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr
                                class="border-t border-slate-200 odd:bg-white even:bg-slate-50 hover:bg-accent/5 transition-colors group">
                                <td class="px-5 py-4">
                                    <input type="checkbox" name="customer_ids[]" value="{{ $customer->customer_id }}"
                                        class="customer-checkbox w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent accent-accent cursor-pointer">
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold border border-slate-200 group-hover:bg-accent group-hover:text-white transition-colors">
                                            {{ substr($customer->customer_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-base">
                                                {{ $customer->customer_name }}
                                            </div>
                                            <div class="text-xs text-slate-500 font-medium">ID:
                                                {{ $customer->customer_id }} · Mob: {{ $customer->mobile_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-600">
                                    ₹{{ number_format($customer->opening_balance, 2) }}</td>
                                <td class="px-5 py-4">
                                    <div
                                        class="font-bold text-lg {{ $customer->final_balance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        ₹{{ number_format(abs($customer->final_balance), 2) }}
                                        <span class="text-[10px] uppercase font-black ml-0.5 opacity-60">
                                            {{ $customer->final_balance >= 0 ? 'Cr' : 'Dr' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            onclick="openLedgerModal('{{ $customer->customer_id }}', '{{ addslashes($customer->customer_name) }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white font-bold text-xs uppercase tracking-wider hover:bg-accent transition shadow-sm hover:shadow-lg">
                                            Statement
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                fill="currentColor" viewBox="0 0 256 256">
                                                <path
                                                    d="M224,128a8,8,0,0,1-8,8H120a8,8,0,0,1,0-16h96A8,8,0,0,1,224,128Zm-8,56H120a8,8,0,0,0,0,16h96a8,8,0,0,0,0-16ZM88,64a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H80A8,8,0,0,1,88,64Zm128,0a8,8,0,0,1-8,8H120a8,8,0,0,1,0-16h88A8,8,0,0,1,216,64ZM40,200h40a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16Zm12-64h28a8,8,0,0,0,0-16H52a8,8,0,0,0,0,16Z">
                                                </path>
                                            </svg>
                                        </button>

                                        <form action="{{ route('calculation.delete.customer', $customer->customer_id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Delete all data for {{ $customer->customer_name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition"
                                                title="Delete customer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 256 256">
                                                    <path
                                                        d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 256 256">
                                            <path
                                                d="M200,32H56A16,16,0,0,0,40,48V208a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V48A16,16,0,0,0,200,32Zm0,176H56V48H200V208ZM184,96a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,96Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,128Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,160Z">
                                            </path>
                                        </svg>
                                        <p class="text-slate-500 font-medium">No customers found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($customers, 'hasPages') && $customers->hasPages())
            <div class="pt-4">
                {{ $customers->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
    </section>
    </div>

    <!-- Ledger Filter Modal -->
    <div id="ledgerModal"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-navy/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 border border-white/20">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900">Generate Statement</h3>
                <button onclick="closeLedgerModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 256 256">
                        <path
                            d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="ledgerForm" method="GET" action="" class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Customer</label>
                    <div id="modalCustomerName"
                        class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Quick Select</label>
                    <select onchange="setQuickDate(this.value)"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60 text-slate-900">
                        <option value="">Custom Range</option>
                        <option value="last_7">Last 7 Days</option>
                        <option value="last_30">Last 30 Days (1 Month)</option>
                        <option value="last_180">Last 6 Months</option>
                        <option value="last_365">Last 1 Year</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">From Date</label>
                        <input type="date" id="modalFromDate" name="from"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">To Date</label>
                        <input type="date" id="modalToDate" name="to" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-accent/60 text-slate-900">
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-900 text-white font-bold text-lg hover:bg-accent transition shadow-xl">
                        View Ledger
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L204.69,128,138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
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

            ['dragenter', 'dragover'].forEach(evt => {
                zone.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('is-dragging');
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                zone.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('is-dragging');
                });
            });
            zone.addEventListener('drop', e => {
                const file = e.dataTransfer.files[0];
                if (file) {
                    input.files = e.dataTransfer.files;
                    setFileName(file);
                }
            });
        }

        bindDropzone('customer_file', 'customer_file_name', 'customer_progress', '.unique-dropzone');
        bindDropzone('transaction_file', 'transaction_file_name', 'transaction_progress', '.unique-dropzone');

        function openLedgerModal(id, name) {
            const modal = document.getElementById('ledgerModal');
            const form = document.getElementById('ledgerForm');
            const nameDisplay = document.getElementById('modalCustomerName');

            nameDisplay.textContent = name;
            form.action = `/ledger/${id}`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function closeLedgerModal() {
            const modal = document.getElementById('ledgerModal');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function setQuickDate(value) {
            const fromInput = document.getElementById('modalFromDate');
            const toInput = document.getElementById('modalToDate');
            const now = new Date();

            let fromDate = new Date();

            if (value === 'last_7') {
                fromDate.setDate(now.getDate() - 7);
            } else if (value === 'last_30') {
                fromDate.setDate(now.getDate() - 30);
            } else if (value === 'last_180') {
                fromDate.setDate(now.getDate() - 180);
            } else if (value === 'last_365') {
                fromDate.setDate(now.getDate() - 365);
            } else {
                return; // Custom range, do nothing
            }

            fromInput.value = fromDate.toISOString().split('T')[0];
            toInput.value = now.toISOString().split('T')[0];
        }

        // Close modal on outside click
        window.onclick = function (event) {
            const modal = document.getElementById('ledgerModal');
            if (event.target == modal) {
                closeLedgerModal();
            }
        }

        // Bulk Selection Logic
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.customer-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCountLabel = document.getElementById('selected-count');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkBar() {
            const checkedCount = document.querySelectorAll('.customer-checkbox:checked').length;

            // Toggle row selection class
            checkboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (cb.checked) {
                    row.classList.add('is-selected');
                } else {
                    row.classList.remove('is-selected');
                }
            });

            if (checkedCount > 0) {
                bulkActionsBar.classList.remove('hidden');
                selectedCountLabel.textContent = checkedCount;
            } else {
                bulkActionsBar.classList.add('hidden');
                selectAll.checked = false;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBulkBar();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateBulkBar();
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                    const allChecked = document.querySelectorAll('.customer-checkbox:checked').length === checkboxes.length;
                    selectAll.checked = allChecked;
                }
            });
        });

        function clearSelection() {
            checkboxes.forEach(cb => cb.checked = false);
            if (selectAll) selectAll.checked = false;
            updateBulkBar();
        }

        function confirmBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            if (confirm(`Are you sure you want to delete ${checkedBoxes.length} selected customers and all their transaction data? This cannot be undone.`)) {
                // Remove any existing hidden inputs first
                const existingInputs = bulkDeleteForm.querySelectorAll('input[name="customer_ids[]"]');
                existingInputs.forEach(el => el.remove());

                // Add new hidden inputs for each selected ID
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'customer_ids[]';
                    input.value = cb.value;
                    bulkDeleteForm.appendChild(input);
                });

                bulkDeleteForm.submit();
            }
        }
    </script>
</body>

</html>