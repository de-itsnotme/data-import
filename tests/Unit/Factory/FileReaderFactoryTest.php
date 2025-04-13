<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Service\DataReader\FileReaderInterface;
use App\Factory\Command\FileReaderFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FileReaderFactoryTest extends TestCase
{
    public function testGetReader()
    {
        $readerInterfaceMock = $this->createMock(FileReaderInterface::class);
        $readerInterfaceMock->method('getSupportedExtension')->willReturn('CSV');

        $factory = new FileReaderFactory([$readerInterfaceMock]);
        $reader = $factory->getReader('foo.csv');

        $this->assertInstanceOf(FileReaderInterface::class, $reader);
    }

    public function testGetReaderThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new FileReaderFactory([]);
        $factory->getReader('foo.jpg');
    }
}
