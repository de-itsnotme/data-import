<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command;

use App\Command\DataImportCommand;
use App\Service\DataWriter\DatabaseWriter;
use App\Service\DataReader\DataReaderService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CsvImportTest extends TestCase
{
    public function testHappyPath(): void
    {
        $dataReaderServiceMock = $this->getMockBuilder(DataReaderService::class)->disableOriginalConstructor()->getMock();
        $dataWriterServiceMock = $this->getMockBuilder(DatabaseWriter::class)->disableOriginalConstructor()->getMock();
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $command = new DataImportCommand($dataReaderServiceMock, $dataWriterServiceMock, $loggerMock);

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($application->find('data:import'));
        $filePath = realpath(dirname(__DIR__) . '/../../resources/uploads/feed.csv');

        $commandTester->execute([
            'filePath' => $filePath,
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Starting Data Import', $output);
        $this->assertStringContainsString('Data import completed successfully!', $output);
        $this->assertStringContainsString('Import Finished', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
    public function testUnsuccessfulImportDueToException(): void
    {
        $dataReaderServiceMock = $this->getMockBuilder(DataReaderService::class)->disableOriginalConstructor()->getMock();
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $dataWriterServiceMock = $this->getMockBuilder(DatabaseWriter::class)->disableOriginalConstructor()->getMock();
        $dataWriterServiceMock->method('putContentsToDatabase')->willThrowException(new \RuntimeException('Some error!'));

        $command = new DataImportCommand($dataReaderServiceMock, $dataWriterServiceMock, $loggerMock);

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($application->find('data:import'));
        $filePath = realpath(dirname(__DIR__) . '/../../resources/uploads/feed.csv');

        $commandTester->execute([
            'filePath' => $filePath,
        ]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('An error occurred during the data import process', $output);
        $this->assertEquals(1, $commandTester->getStatusCode());
    }
}
