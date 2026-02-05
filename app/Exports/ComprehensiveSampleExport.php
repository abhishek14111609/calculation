<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ComprehensiveSampleExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            // DEPOSITS (20 rows)
            ['HDFC Bank', 'bank', '2026-02-01', 50000.00, 'UTR001', 'Customer A', '', '', '', '', 'Payment received'],
            ['ICICI Bank', 'bank', '2026-02-01', 75000.00, 'UTR002', 'Customer B', '', '', '', '', 'Deposit from client'],
            ['Axis Bank', 'bank', '2026-02-01', 30000.00, 'UTR003', 'Customer C', '', '', '', '', 'Invoice payment'],
            ['HDFC Bank', 'bank', '2026-02-01', 45000.00, 'UTR004', 'Customer D', '', '', '', '', 'Advance payment'],
            ['SBI Bank', 'bank', '2026-02-01', 60000.00, 'UTR005', 'Customer E', '', '', '', '', 'Project payment'],
            ['ICICI Bank', 'bank', '2026-02-02', 85000.00, 'UTR006', 'Customer F', '', '', '', '', 'Monthly payment'],
            ['Axis Bank', 'bank', '2026-02-02', 40000.00, 'UTR007', 'Customer G', '', '', '', '', 'Service fee'],
            ['HDFC Bank', 'bank', '2026-02-02', 55000.00, 'UTR008', 'Customer H', '', '', '', '', 'Consultation fee'],
            ['SBI Bank', 'bank', '2026-02-02', 70000.00, 'UTR009', 'Customer I', '', '', '', '', 'Product sale'],
            ['Kotak Bank', 'bank', '2026-02-02', 35000.00, 'UTR010', 'Customer J', '', '', '', '', 'Subscription'],
            ['HDFC Bank', 'bank', '2026-02-03', 48000.00, 'UTR011', 'Customer K', '', '', '', '', 'License fee'],
            ['ICICI Bank', 'bank', '2026-02-03', 62000.00, 'UTR012', 'Customer L', '', '', '', '', 'Maintenance'],
            ['Axis Bank', 'bank', '2026-02-03', 38000.00, 'UTR013', 'Customer M', '', '', '', '', 'Support fee'],
            ['SBI Bank', 'bank', '2026-02-03', 52000.00, 'UTR014', 'Customer N', '', '', '', '', 'Training fee'],
            ['Kotak Bank', 'bank', '2026-02-03', 44000.00, 'UTR015', 'Customer O', '', '', '', '', 'Consulting'],
            ['HDFC Bank', 'bank', '2026-02-04', 58000.00, 'UTR016', 'Customer P', '', '', '', '', 'Project milestone'],
            ['ICICI Bank', 'bank', '2026-02-04', 72000.00, 'UTR017', 'Customer Q', '', '', '', '', 'Annual payment'],
            ['Axis Bank', 'bank', '2026-02-04', 41000.00, 'UTR018', 'Customer R', '', '', '', '', 'Renewal fee'],
            ['SBI Bank', 'bank', '2026-02-04', 66000.00, 'UTR019', 'Customer S', '', '', '', '', 'Commission'],
            ['Kotak Bank', 'bank', '2026-02-04', 39000.00, 'UTR020', 'Customer T', '', '', '', '', 'Bonus payment'],

            // WITHDRAWALS - COMPLETED (15 rows)
            ['HDFC Bank', 'bank', '2026-02-01', 25000.00, 'UTR101', 'Vendor A', '', '', 'completed', '', 'Supplier payment'],
            ['ICICI Bank', 'bank', '2026-02-01', 35000.00, 'UTR102', 'Vendor B', '', '', 'completed', '', 'Office rent'],
            ['Axis Bank', 'bank', '2026-02-01', 18000.00, 'UTR103', 'Vendor C', '', '', 'completed', '', 'Utility bills'],
            ['SBI Bank', 'bank', '2026-02-01', 42000.00, 'UTR104', 'Vendor D', '', '', 'completed', '', 'Equipment purchase'],
            ['Kotak Bank', 'bank', '2026-02-01', 28000.00, 'UTR105', 'Vendor E', '', '', 'completed', '', 'Software license'],
            ['HDFC Bank', 'bank', '2026-02-02', 32000.00, 'UTR106', 'Vendor F', '', '', 'completed', '', 'Marketing expense'],
            ['ICICI Bank', 'bank', '2026-02-02', 45000.00, 'UTR107', 'Vendor G', '', '', 'completed', '', 'Contractor payment'],
            ['Axis Bank', 'bank', '2026-02-02', 22000.00, 'UTR108', 'Vendor H', '', '', 'completed', '', 'Maintenance'],
            ['SBI Bank', 'bank', '2026-02-02', 38000.00, 'UTR109', 'Vendor I', '', '', 'completed', '', 'Inventory'],
            ['Kotak Bank', 'bank', '2026-02-02', 26000.00, 'UTR110', 'Vendor J', '', '', 'completed', '', 'Travel expense'],
            ['HDFC Bank', 'bank', '2026-02-03', 30000.00, 'UTR111', 'Vendor K', '', '', 'completed', '', 'Salary payment'],
            ['ICICI Bank', 'bank', '2026-02-03', 40000.00, 'UTR112', 'Vendor L', '', '', 'completed', '', 'Tax payment'],
            ['Axis Bank', 'bank', '2026-02-03', 20000.00, 'UTR113', 'Vendor M', '', '', 'completed', '', 'Insurance'],
            ['SBI Bank', 'bank', '2026-02-03', 36000.00, 'UTR114', 'Vendor N', '', '', 'completed', '', 'Legal fees'],
            ['Kotak Bank', 'bank', '2026-02-03', 24000.00, 'UTR115', 'Vendor O', '', '', 'completed', '', 'Audit fees'],

            // WITHDRAWALS - PENDING (10 rows)
            ['HDFC Bank', 'bank', '2026-02-04', 15000.00, 'UTR201', 'Vendor P', '', '', 'pending', '', 'Pending approval'],
            ['ICICI Bank', 'bank', '2026-02-04', 22000.00, 'UTR202', 'Vendor Q', '', '', 'pending', '', 'Under review'],
            ['Axis Bank', 'bank', '2026-02-04', 12000.00, 'UTR203', 'Vendor R', '', '', 'pending', '', 'Awaiting funds'],
            ['SBI Bank', 'bank', '2026-02-04', 28000.00, 'UTR204', 'Vendor S', '', '', 'pending', '', 'Processing'],
            ['Kotak Bank', 'bank', '2026-02-04', 16000.00, 'UTR205', 'Vendor T', '', '', 'pending', '', 'Verification pending'],
            ['HDFC Bank', 'bank', '2026-02-05', 19000.00, 'UTR206', 'Vendor U', '', '', 'pending', '', 'Scheduled payment'],
            ['ICICI Bank', 'bank', '2026-02-05', 25000.00, 'UTR207', 'Vendor V', '', '', 'pending', '', 'Future payment'],
            ['Axis Bank', 'bank', '2026-02-05', 14000.00, 'UTR208', 'Vendor W', '', '', 'pending', '', 'Hold'],
            ['SBI Bank', 'bank', '2026-02-05', 31000.00, 'UTR209', 'Vendor X', '', '', 'pending', '', 'Pending clearance'],
            ['Kotak Bank', 'bank', '2026-02-05', 18000.00, 'UTR210', 'Vendor Y', '', '', 'pending', '', 'In queue'],

            // SETTLEMENTS (20 rows)
            ['', '', '2026-02-01', 50000.00, 'UTR301', '', 'HDFC Bank', 'ICICI Bank', '', '', 'Fund transfer'],
            ['', '', '2026-02-01', 30000.00, 'UTR302', '', 'ICICI Bank', 'Axis Bank', '', '', 'Balance adjustment'],
            ['', '', '2026-02-01', 40000.00, 'UTR303', '', 'Axis Bank', 'SBI Bank', '', '', 'Inter-bank settlement'],
            ['', '', '2026-02-01', 25000.00, 'UTR304', '', 'SBI Bank', 'Kotak Bank', '', '', 'Liquidity management'],
            ['', '', '2026-02-01', 35000.00, 'UTR305', '', 'Kotak Bank', 'HDFC Bank', '', '', 'Cash pooling'],
            ['', '', '2026-02-02', 45000.00, 'UTR306', '', 'HDFC Bank', 'Axis Bank', '', '', 'Rebalancing'],
            ['', '', '2026-02-02', 28000.00, 'UTR307', '', 'ICICI Bank', 'SBI Bank', '', '', 'Fund allocation'],
            ['', '', '2026-02-02', 38000.00, 'UTR308', '', 'Axis Bank', 'Kotak Bank', '', '', 'Settlement'],
            ['', '', '2026-02-02', 32000.00, 'UTR309', '', 'SBI Bank', 'HDFC Bank', '', '', 'Transfer'],
            ['', '', '2026-02-02', 42000.00, 'UTR310', '', 'Kotak Bank', 'ICICI Bank', '', '', 'Clearing'],
            ['', '', '2026-02-03', 48000.00, 'UTR311', '', 'HDFC Bank', 'SBI Bank', '', '', 'Netting'],
            ['', '', '2026-02-03', 26000.00, 'UTR312', '', 'ICICI Bank', 'Kotak Bank', '', '', 'Position adjustment'],
            ['', '', '2026-02-03', 36000.00, 'UTR313', '', 'Axis Bank', 'HDFC Bank', '', '', 'Reconciliation'],
            ['', '', '2026-02-03', 29000.00, 'UTR314', '', 'SBI Bank', 'ICICI Bank', '', '', 'Balance transfer'],
            ['', '', '2026-02-03', 39000.00, 'UTR315', '', 'Kotak Bank', 'Axis Bank', '', '', 'Settlement'],
            ['', '', '2026-02-04', 44000.00, 'UTR316', '', 'HDFC Bank', 'Kotak Bank', '', '', 'Fund movement'],
            ['', '', '2026-02-04', 27000.00, 'UTR317', '', 'ICICI Bank', 'HDFC Bank', '', '', 'Reallocation'],
            ['', '', '2026-02-04', 37000.00, 'UTR318', '', 'Axis Bank', 'ICICI Bank', '', '', 'Transfer'],
            ['', '', '2026-02-04', 31000.00, 'UTR319', '', 'SBI Bank', 'Axis Bank', '', '', 'Settlement'],
            ['', '', '2026-02-04', 41000.00, 'UTR320', '', 'Kotak Bank', 'SBI Bank', '', '', 'Clearing'],

            // BANK CLOSINGS (25 rows - 5 banks x 5 days)
            ['HDFC Bank', 'bank', '2026-02-01', '', '', '', '', '', '', 450000.00, 'Daily closing'],
            ['ICICI Bank', 'bank', '2026-02-01', '', '', '', '', '', '', 520000.00, 'Daily closing'],
            ['Axis Bank', 'bank', '2026-02-01', '', '', '', '', '', '', 280000.00, 'Daily closing'],
            ['SBI Bank', 'bank', '2026-02-01', '', '', '', '', '', '', 380000.00, 'Daily closing'],
            ['Kotak Bank', 'bank', '2026-02-01', '', '', '', '', '', '', 310000.00, 'Daily closing'],
            ['HDFC Bank', 'bank', '2026-02-02', '', '', '', '', '', '', 475000.00, 'Daily closing'],
            ['ICICI Bank', 'bank', '2026-02-02', '', '', '', '', '', '', 545000.00, 'Daily closing'],
            ['Axis Bank', 'bank', '2026-02-02', '', '', '', '', '', '', 295000.00, 'Daily closing'],
            ['SBI Bank', 'bank', '2026-02-02', '', '', '', '', '', '', 405000.00, 'Daily closing'],
            ['Kotak Bank', 'bank', '2026-02-02', '', '', '', '', '', '', 325000.00, 'Daily closing'],
            ['HDFC Bank', 'bank', '2026-02-03', '', '', '', '', '', '', 490000.00, 'Daily closing'],
            ['ICICI Bank', 'bank', '2026-02-03', '', '', '', '', '', '', 560000.00, 'Daily closing'],
            ['Axis Bank', 'bank', '2026-02-03', '', '', '', '', '', '', 305000.00, 'Daily closing'],
            ['SBI Bank', 'bank', '2026-02-03', '', '', '', '', '', '', 420000.00, 'Daily closing'],
            ['Kotak Bank', 'bank', '2026-02-03', '', '', '', '', '', '', 340000.00, 'Daily closing'],
            ['HDFC Bank', 'bank', '2026-02-04', '', '', '', '', '', '', 510000.00, 'Daily closing'],
            ['ICICI Bank', 'bank', '2026-02-04', '', '', '', '', '', '', 580000.00, 'Daily closing'],
            ['Axis Bank', 'bank', '2026-02-04', '', '', '', '', '', '', 320000.00, 'Daily closing'],
            ['SBI Bank', 'bank', '2026-02-04', '', '', '', '', '', '', 440000.00, 'Daily closing'],
            ['Kotak Bank', 'bank', '2026-02-04', '', '', '', '', '', '', 355000.00, 'Daily closing'],
            ['HDFC Bank', 'bank', '2026-02-05', '', '', '', '', '', '', 525000.00, 'Daily closing'],
            ['ICICI Bank', 'bank', '2026-02-05', '', '', '', '', '', '', 595000.00, 'Daily closing'],
            ['Axis Bank', 'bank', '2026-02-05', '', '', '', '', '', '', 335000.00, 'Daily closing'],
            ['SBI Bank', 'bank', '2026-02-05', '', '', '', '', '', '', 455000.00, 'Daily closing'],
            ['Kotak Bank', 'bank', '2026-02-05', '', '', '', '', '', '', 370000.00, 'Daily closing'],
        ];
    }

    public function headings(): array
    {
        return [
            'bank_name',
            'bank_type',
            'date',
            'amount',
            'utr',
            'source_name',
            'from_bank',
            'to_bank',
            'status',
            'actual_closing',
            'remark',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // bank_name
            'B' => 12, // bank_type
            'C' => 12, // date
            'D' => 12, // amount
            'E' => 12, // utr
            'F' => 15, // source_name
            'G' => 15, // from_bank
            'H' => 15, // to_bank
            'I' => 12, // status
            'J' => 15, // actual_closing
            'K' => 20, // remark
        ];
    }
}
