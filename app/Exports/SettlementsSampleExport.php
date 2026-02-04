<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SettlementsSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                '2026-02-01',
                'HDFC Bank',
                'ICICI Bank',
                100000.00,
                'UTR777888999',
                'Inter-bank transfer'
            ],
            [
                '2026-02-01',
                'ICICI Bank',
                'Axis Bank',
                50000.00,
                'UTR000111222',
                'Settlement transfer'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'date',
            'from_bank',
            'to_bank',
            'amount',
            'utr',
            'remark'
        ];
    }
}
