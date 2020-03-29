<?php


namespace App\Helpers;


use App\DTOs\CsvDTO;

class CsvHelper
{
    private function __construct() //This is a class that should never be instanced
    {
    }

    /**
     * @param string $string
     * @return CsvDTO|null
     * @throws \App\Exceptions\InvalidColumns
     * @throws \App\Exceptions\InvalidRow
     */
    public static function getArrayFromCsvString(string $string): ?CsvDTO
    {
        $rows = explode("\n", trim($string));
        /**
         * @var CsvDTO $csv
         */
        $csv = null;
        foreach ($rows as $row) {
            $parsedRow = str_getcsv(trim($row));
            if (!$csv) {
                $csv = new CsvDTO($parsedRow);
            } else {
                $csv->putRow($parsedRow);
            }
        }

        return $csv;
    }
}
