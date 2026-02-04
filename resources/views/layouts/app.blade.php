<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank Reconciliation System')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #2d3748;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
        }

        .nav {
            display: flex;
            gap: 1rem;
        }

        .nav a {
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #4a5568;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav a:hover {
            background: #edf2f7;
            color: #2d3748;
        }

        .nav a.active {
            background: #3182ce;
            color: white;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        /* Filter Panel */
        .filter-panel {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4a5568;
        }

        .form-group input,
        .form-group select {
            padding: 0.5rem;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3182ce;
            color: white;
        }

        .btn-primary:hover {
            background: #2c5aa0;
        }

        .btn-secondary {
            background: #718096;
            color: white;
        }

        .btn-secondary:hover {
            background: #4a5568;
        }

        .btn-success {
            background: #38a169;
            color: white;
        }

        .btn-success:hover {
            background: #2f855a;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }

        tr:hover {
            background: #f7fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #38a169;
            font-weight: 500;
        }

        .text-danger {
            color: #e53e3e;
            font-weight: 500;
        }

        .text-warning {
            color: #d69e2e;
            font-weight: 500;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.5rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            color: #4a5568;
        }

        .pagination a:hover {
            background: #edf2f7;
        }

        .pagination .active {
            background: #3182ce;
            color: white;
            border-color: #3182ce;
        }

        /* Upload Section */
        .upload-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .upload-section h3 {
            margin-bottom: 1rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .file-input-wrapper {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-card h4 {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .nav {
                flex-direction: column;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            table {
                font-size: 0.75rem;
            }

            th,
            td {
                padding: 0.5rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="header">
        <div class="header-content">
            <h1>Bank Reconciliation System</h1>
            <nav class="nav">
                <a href="{{ route('reconciliation.index') }}"
                    class="{{ request()->routeIs('reconciliation.index') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('reconciliation.deposits') }}"
                    class="{{ request()->routeIs('reconciliation.deposits*') ? 'active' : '' }}">Deposits</a>
                <a href="{{ route('reconciliation.withdrawals') }}"
                    class="{{ request()->routeIs('reconciliation.withdrawals*') ? 'active' : '' }}">Withdrawals</a>
                <a href="{{ route('reconciliation.settlements') }}"
                    class="{{ request()->routeIs('reconciliation.settlements*') ? 'active' : '' }}">Settlements</a>
                <a href="{{ route('reconciliation.closings') }}"
                    class="{{ request()->routeIs('reconciliation.closings*') ? 'active' : '' }}">Closings</a>
            </nav>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>

</html>