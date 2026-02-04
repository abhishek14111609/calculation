<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class WithdrawalsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Transform each row into a Withdrawal model
     */
    public function model(array $row)
    {
        // Get or create bank
        $bank = Bank::firstOrCreate(
            ['name' => trim($row['bank_name'])],
            ['type' => $row['bank_type'] ?? 'bank']
        );

        return new Withdrawal([
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']),
            'bank_id' => $bank->id,
            'amount' => $row['amount'],
            'utr' => $row['utr'] ?? null,
            'source_name' => $row['source_name'] ?? null,
            'status' => $row['status'] ?? 'completed',
            'remark' => $row['remark'] ?? null,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:255',
            'date' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'utr' => 'nullable|string|max:255',
            'source_name' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,completed',
            'remark' => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'bank_name.required' => 'Bank name is required',
            'date.required' => 'Date is required',
            'amount.required' => 'Amount is required',
            'amount.min' => 'Amount must be greater than 0',
            'status.in' => 'Status must be either pending or completed',
        ];
    }
}
