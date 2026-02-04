@extends('layouts.app')

@section('title', 'Bank Closings')

@section('content')
    <div class="upload-section">
        <h3>Add/Update Closing Balance</h3>
        <form method="POST" action="{{ route('reconciliation.closings.update') }}">
            @csrf
            <div class="filter-grid">
                <div class="form-group">
                    <label for="bank_id">Bank *</label>
                    <select id="bank_id" name="bank_id" required>
                        <option value="">Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="actual_closing">Actual Closing Balance *</label>
                    <input type="number" id="actual_closing" name="actual_closing" step="0.01" required>
                </div>

                <div class="form-group" style="display: flex; align-items: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Save Closing</button>
                </div>
            </div>
        </form>
    </div>

    <div class="filter-panel">
        <form method="GET" action="{{ route('reconciliation.closings') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="filter_bank_id">Bank</label>
                    <select id="filter_bank_id" name="bank_id">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ ($filters['bank_id'] ?? '') == $bank->id ? 'selected' : '' }}>
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
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('reconciliation.closings') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Bank</th>
                    <th class="text-right">Actual Closing</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                    <tr>
                        <td>{{ $closing->date->format('d M Y') }}</td>
                        <td><strong>{{ $closing->bank->name }}</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($closing->actual_closing, 2) }}</strong></td>
                        <td>{{ $closing->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No closing balances recorded</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($closings->hasPages())
            <div class="pagination">
                {{ $closings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection