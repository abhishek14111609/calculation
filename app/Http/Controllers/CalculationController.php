<?php

namespace App\Http\Controllers;

use App\Exports\FilteredLedgerExport;
use App\Imports\CustomersImport;
use App\Imports\TransactionsImport;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\LedgerQuery;
use App\Services\ActivityLogger;

class CalculationController extends Controller
{
    public function index(Request $request)
    {
        $ledger = LedgerQuery::fromRequest($request);
        $customers = $ledger->paginate(10);
        $kpis = $ledger->kpis();

        return view('calculation.index', [
            'customers' => $customers,
            'grandTotalCredit' => $kpis['grandTotalCredit'],
            'grandTotalDebit' => $kpis['grandTotalDebit'],
            'grandFinalBalance' => $kpis['grandFinalBalance'],
        ]);
    }

    public function uploadCustomers(Request $request)
    {
        $request->validate([
            'customer_file' => 'required|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file = $request->file('customer_file');

        $rows = Excel::toCollection(new CustomersImport, $file)->first() ?? collect();

        $imported = 0;
        $failed = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $normalized = $this->applyAliasMap($this->normalizeRowKeys($row->toArray()));

                $customerId = trim((string) ($normalized['customer_id'] ?? ''));
                if ($customerId === '') {
                    $failed++;
                    if (count($errors) < 20) {
                        $errors[] = [
                            'row' => $index + 2,
                            'messages' => ['Missing customer_id'],
                        ];
                    }
                    continue;
                }

                $customerName = trim((string) ($normalized['customer_name'] ?? ''));

                $openingRaw = $normalized['opening_balance'] ?? 0;
                $openingClean = $openingRaw === '' ? 0 : str_replace([',', ' '], '', (string) $openingRaw);
                $openingValue = is_numeric($openingClean) ? (float) $openingClean : 0;

                $data = [
                    'customer_id' => $customerId,
                    'customer_name' => $customerName !== '' ? $customerName : 'Unknown',
                    'mobile_number' => trim((string) ($normalized['mobile_number'] ?? '')),
                    'email' => $normalized['email'] ?? null,
                    'address' => $normalized['address'] ?? null,
                    'opening_balance' => $openingValue,
                ];

                $extraData = collect($normalized)->except([
                    'customer_id',
                    'customer_name',
                    'mobile_number',
                    'email',
                    'address',
                    'opening_balance',
                ])->filter(function ($value) {
                    return $value !== null && $value !== '';
                })->toArray();

                Customer::updateOrCreate(
                    ['customer_id' => $customerId],
                    array_merge($data, ['extra_data' => $extraData ?: null])
                );

                $imported++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to import customers: ' . $e->getMessage());
        }

        if ($failed > 0) {
            $reasonText = $this->formatSkipReasons($errors);
            return back()->with('warning', "Imported {$imported} customers. {$failed} skipped. {$reasonText}");
        }

        ActivityLogger::log('upload_customers', $file->getClientOriginalName(), [
            'rows_imported' => $imported,
            'rows_failed' => $failed,
        ]);

        return back()->with('success', "Imported {$imported} customers successfully.");
    }

    public function uploadTransactions(Request $request)
    {
        $request->validate([
            'transaction_file' => 'required|mimes:csv,xlsx,xls|max:10240',
        ]);

        $file = $request->file('transaction_file');

        $rows = Excel::toCollection(new TransactionsImport, $file)->first() ?? collect();

        $imported = 0;
        $failed = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $normalized = $this->applyAliasMap($this->normalizeRowKeys($row->toArray()));

                $rawDate = $normalized['transaction_date'] ?? null;
                try {
                    $parsedDate = $rawDate ? Carbon::parse($rawDate) : now();
                } catch (\Exception $e) {
                    $parsedDate = now();
                }

                $transactionId = trim((string) ($normalized['transaction_id'] ?? ''));
                $customerId = trim((string) ($normalized['customer_id'] ?? ''));

                $amountRaw = $normalized['amount'] ?? '';
                $amountClean = $amountRaw === '' ? '' : str_replace([',', ' '], '', (string) $amountRaw);
                $amountValue = is_numeric($amountClean) ? (float) $amountClean : null;

                if ($customerId === '' || $amountValue === null) {
                    $failed++;
                    if (count($errors) < 20) {
                        $messages = [];
                        if ($customerId === '') {
                            $messages[] = 'Missing customer_id';
                        }
                        if ($amountValue === null) {
                            $messages[] = 'Amount missing or not numeric';
                        }
                        $errors[] = [
                            'row' => $index + 2,
                            'messages' => $messages ?: ['Invalid row'],
                        ];
                    }
                    continue;
                }

                $typeRaw = strtolower((string) ($normalized['transaction_type'] ?? ''));
                $typeFinal = in_array($typeRaw, ['credit', 'debit'], true) ? $typeRaw : 'credit';

                if (!Customer::where('customer_id', $customerId)->exists()) {
                    Customer::firstOrCreate([
                        'customer_id' => $customerId,
                    ], [
                        'customer_name' => 'Unknown',
                        'opening_balance' => 0,
                    ]);
                }

                $data = [
                    'transaction_id' => $transactionId !== '' ? $transactionId : uniqid('txn_'),
                    'customer_id' => $customerId,
                    'transaction_date' => $parsedDate->toDateString(),
                    'transaction_type' => $typeFinal,
                    'amount' => $amountValue,
                    'remarks' => $normalized['remarks'] ?? null,
                ];

                $extraData = collect($normalized)->except([
                    'transaction_id',
                    'customer_id',
                    'transaction_date',
                    'transaction_type',
                    'amount',
                    'remarks'
                ])->filter(function ($value) {
                    return $value !== null && $value !== '';
                })->toArray();

                Transaction::updateOrCreate(
                    ['transaction_id' => $data['transaction_id']],
                    array_merge($data, ['extra_data' => $extraData ?: null])
                );

                $imported++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to import transactions: ' . $e->getMessage());
        }

        if ($failed > 0) {
            $reasonText = $this->formatSkipReasons($errors);
            return back()->with('warning', "Imported {$imported} transactions. {$failed} skipped. {$reasonText}");
        }

        ActivityLogger::log('upload_transactions', $file->getClientOriginalName(), [
            'rows_imported' => $imported,
            'rows_failed' => $failed,
        ]);

        return back()->with('success', "Imported {$imported} transactions successfully.");
    }

    public function exportExcel(Request $request)
    {
        $ledger = LedgerQuery::fromRequest($request);
        $rows = $ledger->cursor();
        ActivityLogger::log('export', 'ledger_excel', ['filters' => $request->query(), 'rows' => $rows->count()]);

        return Excel::download(new FilteredLedgerExport($rows), 'calculation_report.xlsx');
    }

    public function exportCsv(Request $request)
    {
        $ledger = LedgerQuery::fromRequest($request);
        $rows = $ledger->cursor();
        ActivityLogger::log('export', 'ledger_csv', ['filters' => $request->query(), 'rows' => $rows->count()]);

        return Excel::download(new FilteredLedgerExport($rows), 'calculation_report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function deleteCustomer($customerId)
    {
        DB::transaction(function () use ($customerId) {
            Transaction::where('customer_id', $customerId)->delete();
            Customer::where('customer_id', $customerId)->delete();
        });

        ActivityLogger::log('delete', $customerId, ['cascade' => true]);

        return back()->with('success', 'Customer deleted.');
    }

    private function normalizeRowKeys(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $clean = strtolower(trim((string) $key));
            $clean = str_replace([' ', '.', '-'], '_', $clean);
            $clean = preg_replace('/_{2,}/', '_', $clean);
            $normalized[$clean] = $value;
        }
        return $normalized;
    }

    private function applyAliasMap(array $row): array
    {
        $aliases = [
            'customer_id' => ['customer id', 'cust_id', 'customerid'],
            'customer_name' => ['name', 'customername'],
            'transaction_id' => ['transaction id', 'txn_id', 'id', 'transactionid'],
            'transaction_type' => ['type', 'dr_cr', 'drcr'],
            'amount' => ['amt', 'value'],
        ];

        foreach ($aliases as $canonical => $keys) {
            if (array_key_exists($canonical, $row)) {
                continue;
            }
            foreach ($keys as $alias) {
                if (array_key_exists($alias, $row)) {
                    $row[$canonical] = $row[$alias];
                    break;
                }
            }
        }

        return $row;
    }

    private function formatSkipReasons(array $errors): string
    {
        if (empty($errors)) {
            return '';
        }

        $messages = collect($errors)->map(function ($err) {
            $row = $err['row'] ?? '?';
            $msg = isset($err['messages']) && is_array($err['messages']) ? implode('; ', $err['messages']) : 'Skipped';
            return "Row {$row}: {$msg}";
        })->take(5)->implode(' | ');

        return "Reasons: {$messages}";
    }
}
