<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SettlementsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
            'From Bank',
            'To Bank',
            'Amount',
            'UTR',
            'Remark',
        ];
    }

    /**
     * Map each row
     */
    public function map($settlement): array
    {
        return [
            $settlement->date->format('Y-m-d'),
            $settlement->fromBank->name ?? '',
            $settlement->toBank->name ?? '',
            number_format($settlement->amount, 2),
            $settlement->utr ?? '',
            $settlement->remark ?? '',
        ];
    }
}
