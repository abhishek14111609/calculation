<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleCustomersExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['CUST0001', 'Aditya Sharma', '9876543210', 'aditya@example.com', 'Mumbai, MH', '1000.00'],
            ['CUST0002', 'Priya Patel', '9123456789', 'priya@example.com', 'Surat, GJ', '0.00'],
            ['CUST0003', 'Rajesh Kumar', '8899001122', 'rajesh@example.com', 'Delhi, DL', '550.50'],
        ];
    }

    public function headings(): array
    {
        return ['customer_id', 'customer_name', 'mobile_number', 'email', 'address', 'opening_balance'];
    }
}
