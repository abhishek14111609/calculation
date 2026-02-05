<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Bank;
use App\Services\WithdrawalService;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Http\Requests\UpdateWithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(protected WithdrawalService $withdrawalService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $withdrawals = Withdrawal::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $banks = Bank::orderBy('name')->get();

        return view('reconciliation.withdrawals', compact('withdrawals', 'banks'));
    }

    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.withdrawals-create', compact('banks'));
    }

    public function store(StoreWithdrawalRequest $request)
    {
        $this->withdrawalService->create($request->validated());

        return redirect()
            ->route('reconciliation.withdrawals')
            ->with('success', 'Withdrawal created successfully.');
    }

    public function edit(Withdrawal $withdrawal)
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.withdrawals-edit', compact('withdrawal', 'banks'));
    }

    public function update(UpdateWithdrawalRequest $request, Withdrawal $withdrawal)
    {
        $this->withdrawalService->update($withdrawal, $request->validated());

        return redirect()
            ->route('reconciliation.withdrawals')
            ->with('success', 'Withdrawal updated successfully.');
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $this->withdrawalService->delete($withdrawal);

        return redirect()
            ->route('reconciliation.withdrawals')
            ->with('success', 'Withdrawal deleted successfully.');
    }

    public function restore($id)
    {
        $this->withdrawalService->restore($id);

        return redirect()
            ->route('reconciliation.withdrawals')
            ->with('success', 'Withdrawal restored successfully.');
    }

    public function export(Request $request)
    {
        $withdrawals = Withdrawal::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->get();

        $filename = 'withdrawals_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($withdrawals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Bank', 'Amount', 'Status', 'UTR', 'Source Name', 'Remark', 'Created At']);

            foreach ($withdrawals as $withdrawal) {
                fputcsv($file, [
                    $withdrawal->date->format('Y-m-d'),
                    $withdrawal->bank->name,
                    $withdrawal->amount,
                    $withdrawal->status,
                    $withdrawal->utr,
                    $withdrawal->source_name,
                    $withdrawal->remark,
                    $withdrawal->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
