<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\CsvDataDto;
use App\Mapper\CsvDataMapper;
use App\Schema\CsvFieldSchema;
use App\Validator\CsvFieldsValidator;
use PHPUnit\Framework\TestCase;

class CsvDataMapperTest extends TestCase
{
    public function testMap(): void
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

        $validatorMock = $this->createMock(CsvFieldsValidator::class);

        $mapper = new CsvDataMapper($validatorMock);
        $csvDataDto = $mapper->map($testData);

        $this->assertInstanceOf(CsvDataDto::class, $csvDataDto);
    }
}
