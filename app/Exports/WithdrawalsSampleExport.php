<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WithdrawalsSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'HDFC Bank',
                'bank',
                '2026-02-01',
                30000.00,
                'UTR111222333',
                'Vendor X',
                'completed',
                'Payment to vendor'
            ],
            [
                'ICICI Bank',
                'bank',
                '2026-02-01',
                25000.00,
                'UTR444555666',
                'Customer Y',
                'pending',
                'Withdrawal pending approval'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'bank_name',
            'bank_type',
            'date',
            'amount',
            'utr',
            'source_name',
            'status',
            'remark'
        ];
    }
}
