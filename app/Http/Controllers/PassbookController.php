<?php

namespace App\Http\Controllers;

use App\Services\PassbookService;
use App\Exports\PassbookExport;
use App\Models\Bank;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PassbookController extends Controller
{
    protected $passbookService;

    public function __construct(PassbookService $passbookService)
    {
        $this->passbookService = $passbookService;
    }

    public function index(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'transaction_type' => $request->input('transaction_type', 'all'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $banks = Bank::orderBy('name')->get();
        $passbookData = $this->passbookService->getPassbookData($filters);

        $perPage = 15;
        $currentPage = $request->input('page', 1);
        $transactions = collect($passbookData['transactions']);

        $paginatedTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $transactions->forPage($currentPage, $perPage),
            $transactions->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $passbookData['transactions'] = $paginatedTransactions;

        return view('reconciliation.passbook', compact('passbookData', 'banks', 'filters'));
    }

    public function export(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'transaction_type' => $request->input('transaction_type', 'all'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $passbookData = $this->passbookService->getPassbookData($filters);

        return Excel::download(
            new PassbookExport($passbookData),
            'passbook_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}
