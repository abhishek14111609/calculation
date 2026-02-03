<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterLogExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transactions;
    protected $date;

    public function __construct($transactions, $date)
    {
        $this->transactions = $transactions;
        $this->date = $date;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            ['Master Passbook Report - ' . $this->date],
            [],
            ['ID', 'Customer Name', 'Customer ID', 'Date', 'Time', 'Type', 'Amount', 'Remarks']
        ];
    }

    public function map($txn): array
    {
        return [
            $txn->id,
            $txn->customer->customer_name ?? 'N/A',
            $txn->customer_id,
            $txn->transaction_date,
            $txn->created_at->format('h:i A'),
            ucfirst($txn->transaction_type),
            $txn->amount,
            $txn->remarks,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}
