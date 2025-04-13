<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataReader;

use App\Service\DataReader\CsvFileReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CsvFileReaderTest extends TestCase
{
    public function testGetSupportedExtensionShouldReturnCsv(): void
    {
        $serializerMock = $this->getMockBuilder(Serializer::class)->disableOriginalConstructor()->getMock();

        $reader = new CsvFileReader($serializerMock);

        $this->assertSame('CSV', $reader->getSupportedExtension());
    }

    public function testReadHappyPath(): void
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $reader = new CsvFileReader($serializer);

        $filePath = realpath(dirname(__DIR__) . '/../../resources/uploads/feed.csv');
        $output = $reader->read($filePath);

        $this->assertIsArray($output);
        $this->assertCount(10, $output);
    }
}
