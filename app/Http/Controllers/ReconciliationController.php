<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Settlement;
use App\Models\BankClosing;
use App\Services\ReconciliationService;
use App\Imports\DepositsImport;
use App\Imports\WithdrawalsImport;
use App\Imports\SettlementsImport;
use App\Exports\ReconciliationExport;
use App\Exports\DepositsExport;
use App\Exports\WithdrawalsExport;
use App\Exports\SettlementsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReconciliationController extends Controller
{
    protected $reconciliationService;

    public function __construct(ReconciliationService $reconciliationService)
    {
        $this->reconciliationService = $reconciliationService;
    }

    /**
     * Main reconciliation dashboard
     */
    public function index(Request $request)
    {
        $banks = Bank::orderBy('name')->get();

        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'bank_id' => $request->input('bank_id'),
        ];

        $reconciliationData = $this->reconciliationService->getReconciliationData(
            $filters['start_date'],
            $filters['end_date'],
            $filters['bank_id']
        );

        return view('reconciliation.index', compact('reconciliationData', 'banks', 'filters'));
    }

    /**
     * Deposits page
     */
    public function deposits(Request $request)
    {
        $banks = Bank::orderBy('name')->get();

        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $deposits = $this->reconciliationService->getDeposits($filters)->paginate(15);

        return view('reconciliation.deposits', compact('deposits', 'banks', 'filters'));
    }

    /**
     * Withdrawals page
     */
    public function withdrawals(Request $request)
    {
        $banks = Bank::orderBy('name')->get();

        $filters = [
            'bank_id' => $request->input('bank_id'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $withdrawals = $this->reconciliationService->getWithdrawals($filters)->paginate(15);

        return view('reconciliation.withdrawals', compact('withdrawals', 'banks', 'filters'));
    }

    /**
     * Settlements page
     */
    public function settlements(Request $request)
    {
        $banks = Bank::orderBy('name')->get();

        $filters = [
            'from_bank_id' => $request->input('from_bank_id'),
            'to_bank_id' => $request->input('to_bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $settlements = $this->reconciliationService->getSettlements($filters)->paginate(15);

        return view('reconciliation.settlements', compact('settlements', 'banks', 'filters'));
    }

    /**
     * Bank closings page
     */
    public function closings(Request $request)
    {
        $banks = Bank::orderBy('name')->get();

        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $query = BankClosing::with('bank');

        if ($filters['bank_id']) {
            $query->where('bank_id', $filters['bank_id']);
        }
        if ($filters['start_date']) {
            $query->where('date', '>=', $filters['start_date']);
        }
        if ($filters['end_date']) {
            $query->where('date', '<=', $filters['end_date']);
        }

        $closings = $query->orderBy('date', 'desc')->paginate(15);

        return view('reconciliation.closings', compact('closings', 'banks', 'filters'));
    }

    /**
     * Smart Upload - Centralized upload that auto-detects and routes data
     */
    public function uploadSmart(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $import = new \App\Imports\SmartReconciliationImport();
            Excel::import($import, $request->file('file'));

            DB::commit();

            $report = $import->getReport();

            return redirect()->route('reconciliation.upload.report')
                ->with('upload_report', $report);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Show upload page
     */
    public function showUpload()
    {
        return view('reconciliation.upload');
    }

    /**
     * Show upload report
     */
    public function showUploadReport()
    {
        $report = session('upload_report');

        if (!$report) {
            return redirect()->route('reconciliation.upload');
        }

        return view('reconciliation.upload', compact('report'));
    }

    /**
     * Update bank closing
     */
    public function updateClosing(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'date' => 'required|date',
            'actual_closing' => 'required|numeric',
        ]);

        try {
            BankClosing::updateOrCreate(
                [
                    'bank_id' => $request->bank_id,
                    'date' => $request->date,
                ],
                [
                    'actual_closing' => $request->actual_closing,
                ]
            );

            return redirect()->back()->with('success', 'Closing balance updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Export reconciliation data
     */
    public function exportReconciliation(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'bank_id' => $request->input('bank_id'),
        ];

        $data = $this->reconciliationService->getReconciliationData(
            $filters['start_date'],
            $filters['end_date'],
            $filters['bank_id']
        );

        $format = $request->input('format', 'xlsx');
        $filename = 'reconciliation_' . date('Y-m-d_His') . '.' . $format;

        return Excel::download(new ReconciliationExport($data), $filename);
    }

    /**
     * Export deposits
     */
    public function exportDeposits(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $query = $this->reconciliationService->getDeposits($filters);

        $format = $request->input('format', 'xlsx');
        $filename = 'deposits_' . date('Y-m-d_His') . '.' . $format;

        return Excel::download(new DepositsExport($query), $filename);
    }

    /**
     * Export withdrawals
     */
    public function exportWithdrawals(Request $request)
    {
        $filters = [
            'bank_id' => $request->input('bank_id'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $query = $this->reconciliationService->getWithdrawals($filters);

        $format = $request->input('format', 'xlsx');
        $filename = 'withdrawals_' . date('Y-m-d_His') . '.' . $format;

        return Excel::download(new WithdrawalsExport($query), $filename);
    }

    /**
     * Export settlements
     */
    public function exportSettlements(Request $request)
    {
        $filters = [
            'from_bank_id' => $request->input('from_bank_id'),
            'to_bank_id' => $request->input('to_bank_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
        ];

        $query = $this->reconciliationService->getSettlements($filters);

        $format = $request->input('format', 'xlsx');
        $filename = 'settlements_' . date('Y-m-d_His') . '.' . $format;

        return Excel::download(new SettlementsExport($query), $filename);
    }

    /**
     * Download sample deposits template
     */
    public function downloadSampleDeposits()
    {
        return Excel::download(new \App\Exports\DepositsSampleExport, 'sample_deposits.xlsx');
    }

    /**
     * Download sample withdrawals template
     */
    public function downloadSampleWithdrawals()
    {
        return Excel::download(new \App\Exports\WithdrawalsSampleExport, 'sample_withdrawals.xlsx');
    }

    /**
     * Download sample settlements template
     */
    public function downloadSampleSettlements()
    {
        return Excel::download(new \App\Exports\SettlementsSampleExport, 'sample_settlements.xlsx');
    }

    /**
     * Download smart upload sample template (all-in-one)
     */
    public function downloadSmartSample()
    {
        return Excel::download(new \App\Exports\SmartUploadSampleExport, 'smart_upload_sample.xlsx');
    }

    /**
     * Download comprehensive sample with 90 rows of realistic data
     */
    public function downloadComprehensiveSample()
    {
        return Excel::download(new \App\Exports\ComprehensiveSampleExport, 'comprehensive_sample_90_rows.xlsx');
    }
}
