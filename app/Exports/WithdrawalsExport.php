<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WithdrawalsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Return the query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Define the headings
     */
    public function headings(): array
    {
        return [
            'Date',
            'Bank Name',
            'Amount',
            'UTR',
            'Source Name',
            'Status',
            'Remark',
        ];
    }

    /**
     * Map each row
     */
    public function map($withdrawal): array
    {
        return [
            $withdrawal->date->format('Y-m-d'),
            $withdrawal->bank->name ?? '',
            number_format($withdrawal->amount, 2),
            $withdrawal->utr ?? '',
            $withdrawal->source_name ?? '',
            ucfirst($withdrawal->status),
            $withdrawal->remark ?? '',
        ];
    }
}
