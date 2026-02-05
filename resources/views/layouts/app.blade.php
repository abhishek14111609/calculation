<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank Reconciliation')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            min-height: 100vh;
        }

        .glass-nav {
            background: rgba(30, 27, 75, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .nav-pill {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-pill:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .nav-pill.active {
            background: rgba(99, 102, 241, 0.9);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
        }

        .kpi-card {
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: rgba(99, 102, 241, 0.03);
            transform: scale(1.001);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }

        .gradient-accent {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
        }

        .gradient-green {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .gradient-red {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }

        .gradient-amber {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .gradient-blue {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .currency {
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.02em;
        }

        /* Premium Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link:hover {
            color: #6366f1;
            background: #f9fafb;
            border-color: #c7d2fe;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.1);
        }

        .pagination .page-item.active .page-link {
            color: #ffffff;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-color: #6366f1;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: #d1d5db;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link svg {
            width: 1rem;
            height: 1rem;
        }

        /* Pagination info text */
        .pagination-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .pagination-info svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #0284c7;
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased">
    <nav class="glass-nav sticky top-0 z-[9999] border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-semibold text-lg">Reconciliation</h1>
                        <p class="text-indigo-200 text-xs">Financial Control Center</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reconciliation.index') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.index') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white">Dashboard</a>
                    <a href="{{ route('reconciliation.upload') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.upload*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload
                    </a>
                    <a href="{{ route('reconciliation.deposits') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.deposits*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white">Deposits</a>
                    <a href="{{ route('reconciliation.withdrawals') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.withdrawals*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white">Withdrawals</a>
                    <a href="{{ route('reconciliation.settlements') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.settlements*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white">Settlements</a>
                    <a href="{{ route('reconciliation.closings') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.closings*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white">Closings</a>
                    <a href="{{ route('reconciliation.passbook') }}"
                        class="nav-pill {{ request()->routeIs('reconciliation.passbook*') ? 'active' : '' }} px-4 py-2 rounded-lg text-sm font-medium text-white flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Passbook
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[1600px] mx-auto px-6 py-8">
        @if(session('success'))
            <div
                class="animate-slide-down mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-emerald-800 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="animate-slide-down mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>