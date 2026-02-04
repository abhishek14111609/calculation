<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DepositsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
            'Remark',
        ];
    }

    /**
     * Map each row
     */
    public function map($deposit): array
    {
        return [
            $deposit->date->format('Y-m-d'),
            $deposit->bank->name ?? '',
            number_format($deposit->amount, 2),
            $deposit->utr ?? '',
            $deposit->source_name ?? '',
            $deposit->remark ?? '',
        ];
    }
}
