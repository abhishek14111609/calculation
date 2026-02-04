<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DepositsSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'HDFC Bank',
                'bank',
                '2026-02-01',
                50000.00,
                'UTR123456789',
                'Customer A',
                'Payment received'
            ],
            [
                'ICICI Bank',
                'bank',
                '2026-02-01',
                75000.00,
                'UTR987654321',
                'Exchange B',
                'Deposit from exchange'
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
            'remark'
        ];
    }
}
