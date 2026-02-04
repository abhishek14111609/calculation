<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;

class DepositsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Transform each row into a Deposit model
     */
    public function model(array $row)
    {
        // Get or create bank
        $bank = Bank::firstOrCreate(
            ['name' => trim($row['bank_name'])],
            ['type' => $row['bank_type'] ?? 'bank']
        );

        return new Deposit([
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']),
            'bank_id' => $bank->id,
            'amount' => $row['amount'],
            'utr' => $row['utr'] ?? null,
            'source_name' => $row['source_name'] ?? null,
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
        ];
    }
}
