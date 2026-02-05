<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Bank;
use App\Services\DepositService;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function __construct(protected DepositService $depositService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $deposits = Deposit::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $banks = Bank::orderBy('name')->get();

        return view('reconciliation.deposits', compact('deposits', 'banks'));
    }

    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.deposits-create', compact('banks'));
    }

    public function store(StoreDepositRequest $request)
    {
        $this->depositService->create($request->validated());

        return redirect()
            ->route('reconciliation.deposits')
            ->with('success', 'Deposit created successfully.');
    }

    public function edit(Deposit $deposit)
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.deposits-edit', compact('deposit', 'banks'));
    }

    public function update(UpdateDepositRequest $request, Deposit $deposit)
    {
        $this->depositService->update($deposit, $request->validated());

        return redirect()
            ->route('reconciliation.deposits')
            ->with('success', 'Deposit updated successfully.');
    }

    public function destroy(Deposit $deposit)
    {
        $this->depositService->delete($deposit);

        return redirect()
            ->route('reconciliation.deposits')
            ->with('success', 'Deposit deleted successfully.');
    }

    public function restore($id)
    {
        $this->depositService->restore($id);

        return redirect()
            ->route('reconciliation.deposits')
            ->with('success', 'Deposit restored successfully.');
    }

    public function export(Request $request)
    {
        $deposits = Deposit::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->get();

        $filename = 'deposits_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($deposits) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Bank', 'Amount', 'UTR', 'Source Name', 'Remark', 'Created At']);

            foreach ($deposits as $deposit) {
                fputcsv($file, [
                    $deposit->date->format('Y-m-d'),
                    $deposit->bank->name,
                    $deposit->amount,
                    $deposit->utr,
                    $deposit->source_name,
                    $deposit->remark,
                    $deposit->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
