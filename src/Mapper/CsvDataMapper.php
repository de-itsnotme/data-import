<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CsvDataDto;
use App\Enum\LanguageEnum;
use App\Schema\CsvFieldSchema;
use App\Validator\CsvFieldsValidator;

class CsvDataMapper implements MapperInterface
{
    public function __construct(private CsvFieldsValidator $csvFieldsValidator)
    {
    }

    public function map(array $row): CsvDataDto
    {
        $this->csvFieldsValidator->validate($row);

        $language = $this->getLanguageEnumFromStringValue($row[CsvFieldSchema::FIELD_LANGUAGE]);

        return new CsvDataDto(
            $row[CsvFieldSchema::FIELD_GTIN] ?? '',
                $language,
            $row[CsvFieldSchema::FIELD_TITLE] ?? '',
            $row[CsvFieldSchema::FIELD_PICTURE] ?? '',
            $row[CsvFieldSchema::FIELD_DESCRIPTION] ?? '',
            (float) ($row[CsvFieldSchema::FIELD_PRICE] ?? 0),
            (int) ($row[CsvFieldSchema::FIELD_STOCK] ?? 0),
        );
    }

    private function getLanguageEnumFromStringValue(string $language): LanguageEnum
    {
        return LanguageEnum::from($language);
    }
}
