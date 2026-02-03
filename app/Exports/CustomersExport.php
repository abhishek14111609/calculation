<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{

    public function collection()
    {
        return Customer::with('transactions')->orderBy('customer_name')->get();
    }

    public function headings(): array
    {
        return [
            'Customer ID',
            'Customer Name',
            'Mobile',
            'Email',
            'Address',
            'Opening Balance',
            'Total Credit',
            'Total Debit',
            'Final Balance',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->customer_id,
            $customer->customer_name,
            $customer->mobile_number,
            $customer->email,
            $customer->address,
            $customer->opening_balance,
            $customer->total_credit,
            $customer->total_debit,
            $customer->final_balance,
        ];
    }
}
