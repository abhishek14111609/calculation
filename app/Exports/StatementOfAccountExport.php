<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StatementOfAccountExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    private $customer;
    private $transactions;
    private $openingBalance;
    private $filters;

    public function __construct($customer, $transactions, $openingBalance, $filters)
    {
        $this->customer = $customer;
        $this->transactions = $transactions;
        $this->openingBalance = $openingBalance;
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Statement of Account';
    }

    public function collection()
    {
        $data = collect();

        // Header Info (First few rows for customer info)
        $data->push(['Statement of Account', '', '', '', '', '']);
        $data->push(['Customer:', $this->customer->customer_name, '', '', 'Period:', ($this->filters['from'] ? Carbon::parse($this->filters['from'])->format('d M Y') : 'Start') . ' — ' . Carbon::parse($this->filters['to'])->format('d M Y')]);
        $data->push(['ID:', $this->customer->customer_id, '', '', 'Generated:', now()->format('d M Y, h:i A')]);
        $data->push(['', '', '', '', '', '']); // Spacer

        // Column Headings are handled by headings() method but we can add them manually if we want custom positioning.
        // For simplicity with FromCollection, we'll start data from here.

        // Opening Balance Row
        $data->push([
            '',
            'OPENING BALANCE',
            '',
            $this->openingBalance < 0 ? abs($this->openingBalance) : '',
            $this->openingBalance >= 0 ? $this->openingBalance : '',
            abs($this->openingBalance) . ($this->openingBalance >= 0 ? ' Cr' : ' Dr')
        ]);

        $runningBalance = $this->openingBalance;
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($this->transactions as $txn) {
            if ($txn->transaction_type === 'credit') {
                $runningBalance += (float) $txn->amount;
                $totalCredit += (float) $txn->amount;
            } else {
                $runningBalance -= (float) $txn->amount;
                $totalDebit += (float) $txn->amount;
            }

            $data->push([
                Carbon::parse($txn->transaction_date)->format('d M y'),
                $txn->transaction_type === 'credit' ? 'Receipt' : 'Sales',
                $txn->transaction_id,
                $txn->transaction_type === 'debit' ? (float) $txn->amount : '',
                $txn->transaction_type === 'credit' ? (float) $txn->amount : '',
                abs($runningBalance) . ($runningBalance >= 0 ? ' Cr' : ' Dr')
            ]);
        }

        // Period Totals
        $data->push([
            '',
            'PERIOD TOTALS',
            '',
            $totalDebit,
            $totalCredit,
            ''
        ]);

        // Closing Balance
        $data->push([
            '',
            'CLOSING BALANCE',
            '',
            '',
            abs($runningBalance),
            abs($runningBalance) . ($runningBalance >= 0 ? ' Cr' : ' Dr')
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'DATE',
            'VCH TYPE',
            'VCH NO.',
            'DEBIT',
            'CREDIT',
            'BALANCE'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Custom styling to make it look professional
        return [
            // Style the header "Statement of Account"
            1 => ['font' => ['bold' => true, 'size' => 16]],

            // Style the Column Headings (Row 5 since we have 4 header rows)
            5 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'F1F5F9']
                ]
            ],

            // Bold the Opening Balance Row (Row 6)
            6 => ['font' => ['bold' => true]],

            // Bold the Totals and Closing Balance rows (Last 2 rows)
            $sheet->getHighestRow() - 1 => ['font' => ['bold' => true]],
            $sheet->getHighestRow() => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '0F172A']
                ]
            ],
        ];
    }
}
