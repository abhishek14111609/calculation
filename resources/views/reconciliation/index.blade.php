@extends('layouts.app')

@section('title', 'Reconciliation Dashboard')

@section('content')
    <div class="filter-panel">
        <form method="GET" action="{{ route('reconciliation.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="bank_id">Bank</label>
                    <select id="bank_id" name="bank_id">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary">Clear</a>
                <a href="{{ route('reconciliation.export', request()->query()) }}" class="btn btn-success">Export Excel</a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Type</th>
                    <th class="text-right">Pay IN</th>
                    <th class="text-right">Pay OUT</th>
                    <th class="text-right">Net Settlements</th>
                    <th class="text-right">System Balance</th>
                    <th class="text-right">Actual Closing</th>
                    <th class="text-right">Difference</th>
                    <th class="text-right">Pending</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reconciliationData as $row)
                    <tr>
                        <td><strong>{{ $row['bank_name'] }}</strong></td>
                        <td>{{ ucfirst($row['bank_type']) }}</td>
                        <td class="text-right text-success">₹{{ number_format($row['total_deposits'], 2) }}</td>
                        <td class="text-right text-danger">₹{{ number_format($row['total_withdrawals'], 2) }}</td>
                        <td class="text-right {{ $row['net_settlements'] >= 0 ? 'text-success' : 'text-danger' }}">
                            ₹{{ number_format($row['net_settlements'], 2) }}
                        </td>
                        <td class="text-right"><strong>₹{{ number_format($row['system_balance'], 2) }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($row['actual_closing'], 2) }}</strong></td>
                        <td class="text-right {{ abs($row['difference']) > 0.01 ? 'text-danger' : 'text-success' }}">
                            <strong>₹{{ number_format($row['difference'], 2) }}</strong>
                        </td>
                        <td class="text-right text-warning">₹{{ number_format($row['pending_withdrawals'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($reconciliationData) > 0)
        <div style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 8px;">
            <h4 style="margin-bottom: 0.5rem; color: #856404;">Legend:</h4>
            <ul style="margin-left: 1.5rem; color: #856404;">
                <li><strong>Pay IN:</strong> Total deposits</li>
                <li><strong>Pay OUT:</strong> Total completed withdrawals</li>
                <li><strong>Net Settlements:</strong> Settlements IN minus Settlements OUT</li>
                <li><strong>System Balance:</strong> Pay IN - Pay OUT + Net Settlements</li>
                <li><strong>Difference:</strong> Actual Closing - System Balance</li>
                <li><strong>Pending:</strong> Pending withdrawals (not included in system balance)</li>
            </ul>
        </div>
    @endif
@endsection