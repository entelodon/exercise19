<?php

namespace App\DTOs\interfaces;

interface iStructuredData
{
    public function __construct(array $columns);

    public function getColumns(): array;

    public function putRow(array $row): int;

    public function getRows(): array;

    public function getRow(int $key): array;

    public function getJson(): string;

    public function getArray(): array;
}
