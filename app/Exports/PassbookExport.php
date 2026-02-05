<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PassbookExport implements FromCollection, WithHeadings, WithStyles
{
    protected $passbookData;

    public function __construct($passbookData)
    {
        $this->passbookData = $passbookData;
    }

    public function collection()
    {
        return collect($this->passbookData['transactions'])->map(function ($transaction) {
            return [
                'date' => \Carbon\Carbon::parse($transaction['date'])->format('d-m-Y'),
                'type' => $transaction['type_label'],
                'description' => $transaction['description'],
                'utr' => $transaction['utr'] ?? '-',
                'debit' => $transaction['amount'] < 0 ? '₹' . number_format(abs($transaction['amount']), 2) : '',
                'credit' => $transaction['amount'] > 0 ? '₹' . number_format($transaction['amount'], 2) : '',
                'balance' => '₹' . number_format($transaction['running_balance'], 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Description',
            'UTR',
            'Debit',
            'Credit',
            'Balance',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
