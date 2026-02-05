<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use App\Models\Bank;
use App\Services\SettlementService;
use App\Http\Requests\StoreSettlementRequest;
use App\Http\Requests\UpdateSettlementRequest;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function __construct(protected SettlementService $settlementService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $settlements = Settlement::with(['fromBank', 'toBank'])
            ->when($request->bank_id, function ($q) use ($request) {
                $q->where('from_bank_id', $request->bank_id)
                    ->orWhere('to_bank_id', $request->bank_id);
            })
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $banks = Bank::orderBy('name')->get();

        return view('reconciliation.settlements', compact('settlements', 'banks'));
    }

    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.settlements-create', compact('banks'));
    }

    public function store(StoreSettlementRequest $request)
    {
        $this->settlementService->create($request->validated());

        return redirect()
            ->route('reconciliation.settlements')
            ->with('success', 'Settlement created successfully.');
    }

    public function edit(Settlement $settlement)
    {
        $banks = Bank::orderBy('name')->get();
        return view('reconciliation.settlements-edit', compact('settlement', 'banks'));
    }

    public function update(UpdateSettlementRequest $request, Settlement $settlement)
    {
        $this->settlementService->update($settlement, $request->validated());

        return redirect()
            ->route('reconciliation.settlements')
            ->with('success', 'Settlement updated successfully.');
    }

    public function destroy(Settlement $settlement)
    {
        $this->settlementService->delete($settlement);

        return redirect()
            ->route('reconciliation.settlements')
            ->with('success', 'Settlement deleted successfully.');
    }

    public function restore($id)
    {
        $this->settlementService->restore($id);

        return redirect()
            ->route('reconciliation.settlements')
            ->with('success', 'Settlement restored successfully.');
    }

    public function export(Request $request)
    {
        $settlements = Settlement::with(['fromBank', 'toBank'])
            ->when($request->bank_id, function ($q) use ($request) {
                $q->where('from_bank_id', $request->bank_id)
                    ->orWhere('to_bank_id', $request->bank_id);
            })
            ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
            ->latest('date')
            ->get();

        $filename = 'settlements_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($settlements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'From Bank', 'To Bank', 'Amount', 'UTR', 'Remark', 'Created At']);

            foreach ($settlements as $settlement) {
                fputcsv($file, [
                    $settlement->date->format('Y-m-d'),
                    $settlement->fromBank->name,
                    $settlement->toBank->name,
                    $settlement->amount,
                    $settlement->utr,
                    $settlement->remark,
                    $settlement->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
