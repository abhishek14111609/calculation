@extends('layouts.app')

@section('title', 'Withdrawals')

@section('content')
    <div class="upload-section">
        <h3>Upload Withdrawals</h3>
        <p style="margin-bottom: 1rem; color: #718096;">
            <a href="{{ route('reconciliation.samples.withdrawals') }}" class="btn btn-secondary"
                style="font-size: 0.875rem;">Download Sample Template</a>
        </p>
        <form method="POST" action="{{ route('reconciliation.withdrawals.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="file-input-wrapper">
                <div class="form-group" style="flex: 1;">
                    <label for="file">Excel File (bank_name, date, amount, utr, source_name, status, remark)</label>
                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

    <div class="filter-panel">
        <form method="GET" action="{{ route('reconciliation.withdrawals') }}">
            <div class="filter-grid">
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

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="min_amount">Min Amount</label>
                    <input type="number" id="min_amount" name="min_amount" step="0.01"
                        value="{{ $filters['min_amount'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="max_amount">Max Amount</label>
                    <input type="number" id="max_amount" name="max_amount" step="0.01"
                        value="{{ $filters['max_amount'] ?? '' }}">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('reconciliation.withdrawals') }}" class="btn btn-secondary">Clear</a>
                <a href="{{ route('reconciliation.withdrawals.export', request()->query()) }}"
                    class="btn btn-success">Export Excel</a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Bank</th>
                    <th>Source Name</th>
                    <th>UTR</th>
                    <th class="text-right">Amount</th>
                    <th>Status</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->date->format('d M Y') }}</td>
                        <td><strong>{{ $withdrawal->bank->name }}</strong></td>
                        <td>{{ $withdrawal->source_name ?? '-' }}</td>
                        <td>{{ $withdrawal->utr ?? '-' }}</td>
                        <td class="text-right text-danger"><strong>₹{{ number_format($withdrawal->amount, 2) }}</strong></td>
                        <td>
                            <span class="{{ $withdrawal->status == 'completed' ? 'text-success' : 'text-warning' }}">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                        <td>{{ $withdrawal->remark ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No withdrawals found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($withdrawals->hasPages())
            <div class="pagination">
                {{ $withdrawals->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection