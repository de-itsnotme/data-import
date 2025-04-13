<?php

declare(strict_types=1);

namespace App\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DataImportCommandTest extends KernelTestCase
{
    public function testExecuteCommandWithRealServices(): void
    {
        $this->markTestSkipped();

        // Boot the Symfony kernel
        self::bootKernel();

        // Get the application using the real kernel
        $application = new Application(self::$kernel);

        // Get the command (Symfony will inject real services)
        $command = $application->find('data:import');

        $commandTester = new CommandTester($command);

        // Provide path to a small test CSV file
        $testFilePath = __DIR__ . 'feed.csv';

        // Ensure test file exists
        $this->assertFileExists($testFilePath);

        // Execute the command with test CSV
        $commandTester->execute([
            'filePath' => $testFilePath,
        ]);

        // Assert output
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Starting Data import...', $output);
        $this->assertStringContainsString('Success!', $output);

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
