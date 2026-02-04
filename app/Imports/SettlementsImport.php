<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\Settlement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SettlementsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * Transform each row into a Settlement model
     */
    public function model(array $row)
    {
        // Get or create from bank
        $fromBank = Bank::firstOrCreate(
            ['name' => trim($row['from_bank'])],
            ['type' => 'bank']
        );

        // Get or create to bank
        $toBank = Bank::firstOrCreate(
            ['name' => trim($row['to_bank'])],
            ['type' => 'bank']
        );

        return new Settlement([
            'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']),
            'from_bank_id' => $fromBank->id,
            'to_bank_id' => $toBank->id,
            'amount' => $row['amount'],
            'utr' => $row['utr'] ?? null,
            'remark' => $row['remark'] ?? null,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'from_bank' => 'required|string|max:255',
            'to_bank' => 'required|string|max:255|different:from_bank',
            'date' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'utr' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'from_bank.required' => 'Source bank is required',
            'to_bank.required' => 'Destination bank is required',
            'to_bank.different' => 'Source and destination banks must be different',
            'date.required' => 'Date is required',
            'amount.required' => 'Amount is required',
            'amount.min' => 'Amount must be greater than 0',
        ];
    }
}
