<?php

namespace App\Http\Controllers;

use App\Models\BankClosing;
use App\Models\Bank;
use App\Services\BankClosingService;
use App\Http\Requests\StoreBankClosingRequest;
use App\Http\Requests\UpdateBankClosingRequest;
use Illuminate\Http\Request;

class BankClosingController extends Controller
{
    public function __construct(protected BankClosingService $bankClosingService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $closings = BankClosing::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $banks = Bank::orderBy('name')->get();

        return view('reconciliation.closings', compact('closings', 'banks'));
    }

    public function store(StoreBankClosingRequest $request)
    {
        $this->bankClosingService->create($request->validated());

        return redirect()
            ->route('reconciliation.closings')
            ->with('success', 'Bank closing created successfully.');
    }

    public function update(UpdateBankClosingRequest $request, BankClosing $closing)
    {
        $this->bankClosingService->update($closing, $request->validated());

        return redirect()
            ->route('reconciliation.closings')
            ->with('success', 'Bank closing updated successfully.');
    }

    public function destroy(BankClosing $closing)
    {
        $this->bankClosingService->delete($closing);

        return redirect()
            ->route('reconciliation.closings')
            ->with('success', 'Bank closing deleted successfully.');
    }

    public function restore($id)
    {
        $this->bankClosingService->restore($id);

        return redirect()
            ->route('reconciliation.closings')
            ->with('success', 'Bank closing restored successfully.');
    }

    public function export(Request $request)
    {
        $closings = BankClosing::with('bank')
            ->when($request->bank_id, fn($q) => $q->where('bank_id', $request->bank_id))
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->get();

        $filename = 'bank_closings_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($closings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Bank', 'Actual Closing', 'Created At']);

            foreach ($closings as $closing) {
                fputcsv($file, [
                    $closing->date->format('Y-m-d'),
                    $closing->bank->name,
                    $closing->actual_closing,
                    $closing->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
