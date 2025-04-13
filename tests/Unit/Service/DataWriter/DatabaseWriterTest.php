<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataWriter;

use App\Dto\CsvDataDto;
use App\Enum\LanguageEnum;
use App\Mapper\CsvDataMapper;
use App\Schema\CsvFieldSchema;
use App\Service\DataWriter\DatabaseWriter;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DatabaseWriterTest extends TestCase
{
    public function testPutContentsToDatabase(): void
    {
        $testData = [
            [
                CsvFieldSchema::FIELD_GTIN => '7034621736823',
                CsvFieldSchema::FIELD_LANGUAGE => 'en',
                CsvFieldSchema::FIELD_TITLE => 'Product 1',
                CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image1.jpg',
                CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 1.',
                CsvFieldSchema::FIELD_PRICE => 738.7,
                CsvFieldSchema::FIELD_STOCK => 100,
            ],
            [
                CsvFieldSchema::FIELD_GTIN => '7034621736824',
                CsvFieldSchema::FIELD_LANGUAGE => 'it',
                CsvFieldSchema::FIELD_TITLE => 'Product 2',
                CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image2.jpg',
                CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 2.',
                CsvFieldSchema::FIELD_PRICE => 638.7,
                CsvFieldSchema::FIELD_STOCK => 200,
            ]
        ];

        $csvDataDto = new CsvDataDto(
            $testData[0][CsvFieldSchema::FIELD_GTIN],
            LanguageEnum::from($testData[0][CsvFieldSchema::FIELD_LANGUAGE]),
            $testData[0][CsvFieldSchema::FIELD_TITLE],
            $testData[0][CsvFieldSchema::FIELD_PICTURE],
            $testData[0][CsvFieldSchema::FIELD_DESCRIPTION],
            $testData[0][CsvFieldSchema::FIELD_PRICE],
            $testData[0][CsvFieldSchema::FIELD_STOCK],
        );

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock->expects($this->exactly(2))->method('persist');
        $entityManagerMock->expects($this->once())->method('flush');

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('info')->with($this->stringContains('2'));

        $csvDataMapper = $this->createMock(CsvDataMapper::class);
        $csvDataMapper->method('map')->willReturn($csvDataDto);

        $service = new DatabaseWriter($entityManagerMock, $loggerMock, $csvDataMapper);

        $service->putContentsToDatabase($testData);
    }
}
