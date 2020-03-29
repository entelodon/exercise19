<?php


namespace App\DTOs;

use App\DTOs\interfaces\iStructuredData;
use App\Exceptions\InvalidColumns;
use App\Exceptions\InvalidRow;
use App\Exceptions\InvalidRowNotFound;

class CsvDTO implements iStructuredData
{
    /**
     * @var array $columns
     */
    private $columns;
    /**
     * @var array $rows
     */
    private $rows = [];

    /**
     * @var int $columnsLength
     */
    private $columnsLength;

    public function __construct(array $columns)
    {
        $this->columnsLength = count($columns);
        if ($this->columnsLength === 0) {
            throw new InvalidColumns('There are no provided columns.');
        }
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $row
     * @return int
     * Returns the key of the added element
     */
    public function putRow(array $row): int
    {
        if ($this->columnsLength !== count($row)) {
            throw new InvalidRow('The number of the columns in the row given, does not match the number of the columns for the whole collection.');
        }
        $record = [];
        foreach ($row as $key => $value) {
            $record[$this->columns[$key]] = $value;
        }
        $this->rows[] = $record;
        return array_key_last($this->rows);
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getRow(int $key): array
    {
        if (!array_key_exists($key, $this->rows)) {
            throw new InvalidRowNotFound('The requested row does not exist.');
        }
        return $this->rows[$key];
    }

    public function getJson(): string
    {
        return json_encode($this->getArray());
    }

    public function getArray(): array
    {
        return [
            'columns' => $this->columns,
            'rows' => $this->rows
        ];
    }
}
