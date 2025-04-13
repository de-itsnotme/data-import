<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\DataReader;

use App\Service\DataReader\DataReaderService;
use App\Service\DataReader\FileReaderInterface;
use App\Factory\Command\FileReaderFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DataReaderServiceTest extends TestCase
{
    public function testRead(): void
    {
        $readerInterfaceMock = $this->createMock(FileReaderInterface::class);
        $readerInterfaceMock->method('read')->willReturn(['data']);
        $readerInterfaceMock->method('getSupportedExtension')->willReturn('CSV');

        $factory = new FileReaderFactory([$readerInterfaceMock]);
        $readerService = new DataReaderService($factory);
        $filePath = realpath(dirname(__DIR__) . '/../../resources/uploads/feed.csv');;
        $values = $readerService->getContentsFromFile($filePath);

        $this->assertEquals(['data'], $values);
    }

    public function testReaderReturnsArray(): void
    {
        $fileReaderFactoryMock = $this->createMock(FileReaderFactory::class);

        $filePath = realpath(dirname(__DIR__) . '/../../resources/uploads/feed.csv');;
        $reader = new DataReaderService($fileReaderFactoryMock);
        $values = $reader->getContentsFromFile($filePath);

        $this->assertIsArray($values);
    }

    public function testReaderThrowsExceptionWhenExtensionIsNotSupported(): void
    {
        $this->expectException(RuntimeException::class);

        $fileReaderFactoryMock = new FileReaderFactory([]);
        $reader = new DataReaderService($fileReaderFactoryMock);
        $filePath = dirname(__DIR__) . '/../../resources/uploads/foo.csv'; // Do not use realpath here, we want to trigger the error from class.
        $values = $reader->getContentsFromFile($filePath);
    }
}
