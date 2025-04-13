<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CsvDataDto;

interface MapperInterface
{
    public function map(array $row): CsvDataDto;
}
