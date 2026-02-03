<?php

namespace App\Services;

use Illuminate\Support\LazyCollection;

class CsvService
{
    /**
     * Read a CSV file and return a LazyCollection of records
     * 
     * @param string $filePath
     * @return LazyCollection
     */
    public function parseCsv($filePath)
    {
        return LazyCollection::make(function () use ($filePath) {
            $handle = fopen($filePath, 'r');

            // Get header row
            $header = fgetcsv($handle);

            if (!$header) {
                return;
            }

            // Normalize headers (lowercase, snake_case) making sure to strip BOM
            $header = array_map(function ($h) {
                $h = preg_replace('/[\x{FEFF}]/u', '', $h); // Remove BOM
                return \Illuminate\Support\Str::snake(strtolower(trim($h)));
            }, $header);

            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows or rows with mismatched column count
                if (count($row) !== count($header)) {
                    continue;
                }

                yield array_combine($header, $row);
            }

            fclose($handle);
        });
    }
}
