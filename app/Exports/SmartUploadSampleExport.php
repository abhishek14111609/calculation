<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SmartUploadSampleExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            // Deposit examples
            [
                'bank_name' => 'HDFC Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => 50000.00,
                'utr' => 'UTR001',
                'source_name' => 'Customer A',
                'from_bank' => '',
                'to_bank' => '',
                'status' => '',
                'actual_closing' => '',
                'remark' => 'Payment received',
            ],
            [
                'bank_name' => 'ICICI Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => 75000.00,
                'utr' => 'UTR002',
                'source_name' => 'Customer B',
                'from_bank' => '',
                'to_bank' => '',
                'status' => '',
                'actual_closing' => '',
                'remark' => 'Deposit',
            ],

            // Withdrawal examples
            [
                'bank_name' => 'HDFC Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => 30000.00,
                'utr' => 'UTR003',
                'source_name' => 'Vendor X',
                'from_bank' => '',
                'to_bank' => '',
                'status' => 'completed',
                'actual_closing' => '',
                'remark' => 'Payment made',
            ],
            [
                'bank_name' => 'ICICI Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => 15000.00,
                'utr' => 'UTR004',
                'source_name' => 'Vendor Y',
                'from_bank' => '',
                'to_bank' => '',
                'status' => 'pending',
                'actual_closing' => '',
                'remark' => 'Pending withdrawal',
            ],

            // Settlement examples
            [
                'bank_name' => '',
                'bank_type' => '',
                'date' => '2026-02-01',
                'amount' => 20000.00,
                'utr' => 'UTR005',
                'source_name' => '',
                'from_bank' => 'HDFC Bank',
                'to_bank' => 'ICICI Bank',
                'status' => '',
                'actual_closing' => '',
                'remark' => 'Inter-bank transfer',
            ],
            [
                'bank_name' => '',
                'bank_type' => '',
                'date' => '2026-02-01',
                'amount' => 10000.00,
                'utr' => 'UTR006',
                'source_name' => '',
                'from_bank' => 'ICICI Bank',
                'to_bank' => 'Axis Bank',
                'status' => '',
                'actual_closing' => '',
                'remark' => 'Settlement',
            ],

            // Bank closing examples
            [
                'bank_name' => 'HDFC Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => '',
                'utr' => '',
                'source_name' => '',
                'from_bank' => '',
                'to_bank' => '',
                'status' => '',
                'actual_closing' => 70000.00,
                'remark' => 'Daily closing balance',
            ],
            [
                'bank_name' => 'ICICI Bank',
                'bank_type' => 'bank',
                'date' => '2026-02-01',
                'amount' => '',
                'utr' => '',
                'source_name' => '',
                'from_bank' => '',
                'to_bank' => '',
                'status' => '',
                'actual_closing' => 120000.00,
                'remark' => 'Daily closing balance',
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
            'from_bank',
            'to_bank',
            'status',
            'actual_closing',
            'remark',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
