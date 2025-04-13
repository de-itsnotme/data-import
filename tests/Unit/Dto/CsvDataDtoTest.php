<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\CsvDataDto;
use App\Enum\LanguageEnum;
use App\Schema\CsvFieldSchema;
use PHPUnit\Framework\TestCase;

class CsvDataDtoTest extends TestCase
{
    public function testCsvDataDto(): void
    {
        $testData = [
            CsvFieldSchema::FIELD_GTIN => '7034621736823',
            CsvFieldSchema::FIELD_LANGUAGE => 'en',
            CsvFieldSchema::FIELD_TITLE => 'Product 1',
            CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image1.jpg',
            CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 1.',
            CsvFieldSchema::FIELD_PRICE => 738.7,
            CsvFieldSchema::FIELD_STOCK => 100,
        ];

        $dto = new CsvDataDto(
            $testData[CsvFieldSchema::FIELD_GTIN],
            LanguageEnum::from($testData[CsvFieldSchema::FIELD_LANGUAGE]),
            $testData[CsvFieldSchema::FIELD_TITLE],
            $testData[CsvFieldSchema::FIELD_PICTURE],
            $testData[CsvFieldSchema::FIELD_DESCRIPTION],
            $testData[CsvFieldSchema::FIELD_PRICE],
            $testData[CsvFieldSchema::FIELD_STOCK],
        );

        $actualData = [
            CsvFieldSchema::FIELD_GTIN => $dto->gtin,
            CsvFieldSchema::FIELD_LANGUAGE => $dto->language->value,
            CsvFieldSchema::FIELD_TITLE => $dto->title,
            CsvFieldSchema::FIELD_PICTURE => $dto->picture,
            CsvFieldSchema::FIELD_DESCRIPTION => $dto->description,
            CsvFieldSchema::FIELD_PRICE => $dto->price,
            CsvFieldSchema::FIELD_STOCK => $dto->stock,
        ];

        $this->assertSame($actualData, $testData);
    }
}
