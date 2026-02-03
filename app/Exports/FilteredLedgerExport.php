<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FilteredLedgerExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Customer ID',
            'Customer Name',
            'Mobile',
            'Email',
            'Opening Balance',
            'Total Credit',
            'Total Debit',
            'Final Balance',
            'Last Transaction Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->customer_id,
            $row->customer_name,
            $row->mobile_number,
            $row->email,
            $row->opening_balance,
            $row->total_credit,
            $row->total_debit,
            $row->final_balance,
            $row->last_txn_date,
        ];
    }
}
