<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleTransactionsExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['TXN101', 'CUST0001', '2026-01-01', 'debit', '1500.00', 'Opening Sale'],
            ['TXN102', 'CUST0001', '2026-01-15', 'credit', '500.00', 'Cash Received'],
            ['TXN103', 'CUST0002', '2026-02-01', 'debit', '2000.00', 'Material Purchase'],
            ['TXN104', 'CUST0001', '2026-02-10', 'debit', '300.00', 'Late Fee'],
            ['TXN105', 'CUST0002', '2026-02-25', 'credit', '1000.00', 'Bank Transfer'],
        ];
    }

    public function headings(): array
    {
        return ['transaction_id', 'customer_id', 'transaction_date', 'transaction_type', 'amount', 'remarks'];
    }
}
