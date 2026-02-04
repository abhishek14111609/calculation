<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReconciliationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return the collection of data
     */
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * Define the headings
     */
    public function headings(): array
    {
        return [
            'Bank Name',
            'Type',
            'Pay IN',
            'Pay OUT',
            'Settlements IN',
            'Settlements OUT',
            'Net Settlements',
            'System Balance',
            'Actual Closing',
            'Difference',
            'Pending Withdrawals',
        ];
    }

    /**
     * Map each row
     */
    public function map($row): array
    {
        return [
            $row['bank_name'] ?? '',
            $row['bank_type'] ?? '',
            number_format($row['total_deposits'] ?? 0, 2),
            number_format($row['total_withdrawals'] ?? 0, 2),
            number_format($row['settlements_in'] ?? 0, 2),
            number_format($row['settlements_out'] ?? 0, 2),
            number_format($row['net_settlements'] ?? 0, 2),
            number_format($row['system_balance'] ?? 0, 2),
            number_format($row['actual_closing'] ?? 0, 2),
            number_format($row['difference'] ?? 0, 2),
            number_format($row['pending_withdrawals'] ?? 0, 2),
        ];
    }
}
