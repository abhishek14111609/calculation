<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements WithHeadingRow
{
    // This class is used just to utilize Excel::toArray with heading row support
}
