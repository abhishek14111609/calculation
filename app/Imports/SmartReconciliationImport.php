<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Settlement;
use App\Models\BankClosing;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartReconciliationImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private $report = [
        'total_rows' => 0,
        'deposits_inserted' => 0,
        'withdrawals_inserted' => 0,
        'settlements_inserted' => 0,
        'closings_updated' => 0,
        'failed_rows' => 0,
        'banks_created' => [],
        'errors' => [],
    ];

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                $this->report['total_rows']++;

                $normalizedRow = $this->normalizeRow($row->toArray());

                if ($this->isEmptyRow($normalizedRow)) {
                    continue;
                }

                $this->processRow($normalizedRow, $rowNumber);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->report['errors'][] = "Transaction failed: " . $e->getMessage();
        }
    }

    private function processRow(array $row, int $rowNumber)
    {
        $rowType = strtolower(trim((string)($row['row_type'] ?? '')));

        if (empty($rowType)) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber}: row_type is required";
            return;
        }

        if (!in_array($rowType, ['deposit', 'withdrawal', 'settlement', 'closing'], true)) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber}: Invalid row_type '{$rowType}'. Must be: deposit, withdrawal, settlement, or closing";
            return;
        }

        switch ($rowType) {
            case 'deposit':
                $this->handleDeposit($row, $rowNumber);
                break;
            case 'withdrawal':
                $this->handleWithdrawal($row, $rowNumber);
                break;
            case 'settlement':
                $this->handleSettlement($row, $rowNumber);
                break;
            case 'closing':
                $this->handleClosing($row, $rowNumber);
                break;
        }
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(trim((string)$key));
            $normalized[$normalizedKey] = is_string($value) ? trim($value) : $value;
        }
        return $normalized;
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (!empty($value) && $value !== null && $value !== '') {
                return false;
            }
        }
        return true;
    }

    private function handleDeposit(array $row, int $rowNumber)
    {
        $validator = Validator::make($row, [
            'bank_name' => 'required|string',
            'date' => 'required',
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (deposit): " . implode(', ', $validator->errors()->all());
            return;
        }

        try {
            $bankType = !empty($row['bank_type']) ? strtolower(trim($row['bank_type'])) : 'bank';
            $bank = $this->getOrCreateBank($row['bank_name'], $bankType);

            Deposit::create([
                'date' => $this->parseDate($row['date']),
                'bank_id' => $bank->id,
                'amount' => $row['amount'],
                'utr' => $row['utr'] ?? null,
                'source_name' => $row['source_name'] ?? null,
                'remark' => $row['remark'] ?? null,
            ]);

            $this->report['deposits_inserted']++;
        } catch (\Exception $e) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (deposit): " . $e->getMessage();
        }
    }

    private function handleWithdrawal(array $row, int $rowNumber)
    {
        $validator = Validator::make($row, [
            'bank_name' => 'required|string',
            'date' => 'required',
            'amount' => 'required|numeric|gt:0',
            'status' => 'required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (withdrawal): " . implode(', ', $validator->errors()->all());
            return;
        }

        try {
            $bankType = !empty($row['bank_type']) ? strtolower(trim($row['bank_type'])) : 'bank';
            $bank = $this->getOrCreateBank($row['bank_name'], $bankType);

            Withdrawal::create([
                'date' => $this->parseDate($row['date']),
                'bank_id' => $bank->id,
                'amount' => $row['amount'],
                'utr' => $row['utr'] ?? null,
                'source_name' => $row['source_name'] ?? null,
                'status' => strtolower(trim($row['status'])),
                'remark' => $row['remark'] ?? null,
            ]);

            $this->report['withdrawals_inserted']++;
        } catch (\Exception $e) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (withdrawal): " . $e->getMessage();
        }
    }

    private function handleSettlement(array $row, int $rowNumber)
    {
        $validator = Validator::make($row, [
            'from_bank' => 'required|string',
            'to_bank' => 'required|string|different:from_bank',
            'date' => 'required',
            'amount' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (settlement): " . implode(', ', $validator->errors()->all());
            return;
        }

        try {
            $fromBank = $this->getOrCreateBank($row['from_bank'], 'bank');
            $toBank = $this->getOrCreateBank($row['to_bank'], 'bank');

            Settlement::create([
                'date' => $this->parseDate($row['date']),
                'from_bank_id' => $fromBank->id,
                'to_bank_id' => $toBank->id,
                'amount' => $row['amount'],
                'utr' => $row['utr'] ?? null,
                'remark' => $row['remark'] ?? null,
            ]);

            $this->report['settlements_inserted']++;
        } catch (\Exception $e) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (settlement): " . $e->getMessage();
        }
    }

    private function handleClosing(array $row, int $rowNumber)
    {
        $validator = Validator::make($row, [
            'bank_name' => 'required|string',
            'date' => 'required',
            'actual_closing' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (closing): " . implode(', ', $validator->errors()->all());
            return;
        }

        try {
            $bankType = !empty($row['bank_type']) ? strtolower(trim($row['bank_type'])) : 'bank';
            $bank = $this->getOrCreateBank($row['bank_name'], $bankType);

            BankClosing::updateOrCreate(
                [
                    'date' => $this->parseDate($row['date']),
                    'bank_id' => $bank->id,
                ],
                [
                    'actual_closing' => $row['actual_closing'],
                ]
            );

            $this->report['closings_updated']++;
        } catch (\Exception $e) {
            $this->report['failed_rows']++;
            $this->report['errors'][] = "Row {$rowNumber} (closing): " . $e->getMessage();
        }
    }

    private function getOrCreateBank(string $name, string $type = 'bank'): Bank
    {
        $name = trim($name);
        $type = strtolower(trim($type));

        if (!in_array($type, ['bank', 'exchange', 'wallet'], true)) {
            $type = 'bank';
        }

        $bank = Bank::where('name', $name)->first();

        if (!$bank) {
            $bank = Bank::create([
                'name' => $name,
                'type' => $type,
            ]);

            if (!in_array($name, $this->report['banks_created'], true)) {
                $this->report['banks_created'][] = $name;
            }
        }

        return $bank;
    }

    private function parseDate($date)
    {
        if ($date instanceof \DateTime) {
            return Carbon::instance($date)->format('Y-m-d');
        }

        if (is_numeric($date)) {
            return Carbon::createFromFormat('Y-m-d', '1899-12-30')->addDays($date)->format('Y-m-d');
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Invalid date format: {$date}");
        }
    }

    public function getReport(): array
    {
        return $this->report;
    }
}
