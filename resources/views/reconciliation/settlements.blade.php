@extends('layouts.app')

@section('title', 'Settlements')

@section('content')
    <div class="upload-section">
        <h3>Upload Settlements</h3>
        <p style="margin-bottom: 1rem; color: #718096;">
            <a href="{{ route('reconciliation.samples.settlements') }}" class="btn btn-secondary"
                style="font-size: 0.875rem;">Download Sample Template</a>
        </p>
        <form method="POST" action="{{ route('reconciliation.settlements.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="file-input-wrapper">
                <div class="form-group" style="flex: 1;">
                    <label for="file">Excel File (from_bank, to_bank, date, amount, utr, remark)</label>
                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

    <div class="filter-panel">
        <form method="GET" action="{{ route('reconciliation.settlements') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="from_bank_id">From Bank</label>
                    <select id="from_bank_id" name="from_bank_id">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['from_bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="to_bank_id">To Bank</label>
                    <select id="to_bank_id" name="to_bank_id">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['to_bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }}
                            </option>
                        @endforeach
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
                <a href="{{ route('reconciliation.settlements') }}" class="btn btn-secondary">Clear</a>
                <a href="{{ route('reconciliation.settlements.export', request()->query()) }}"
                    class="btn btn-success">Export Excel</a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From Bank</th>
                    <th>To Bank</th>
                    <th>UTR</th>
                    <th class="text-right">Amount</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settlements as $settlement)
                    <tr>
                        <td>{{ $settlement->date->format('d M Y') }}</td>
                        <td><strong>{{ $settlement->fromBank->name }}</strong></td>
                        <td><strong>{{ $settlement->toBank->name }}</strong></td>
                        <td>{{ $settlement->utr ?? '-' }}</td>
                        <td class="text-right"><strong>₹{{ number_format($settlement->amount, 2) }}</strong></td>
                        <td>{{ $settlement->remark ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No settlements found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($settlements->hasPages())
            <div class="pagination">
                {{ $settlements->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection