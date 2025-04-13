<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\LanguageEnum;

class CsvDataDto
{
    public function __construct(
        public string $gtin,
        public LanguageEnum $language,
        public string $title,
        public string $picture,
        public string $description,
        public float $price,
        public int $stock,
    ) {
    }
}
