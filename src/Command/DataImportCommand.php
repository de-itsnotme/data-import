<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\DataWriter\DatabaseWriter;
use App\Service\DataReader\DataReaderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'data:import',
    description: 'Import data (e.g., CSV) into the database',
)]
class DataImportCommand extends Command
{
    public function __construct(
        private DataReaderService $dataReaderService,
        private DatabaseWriter $dataWriterService,
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'filePath',
                InputArgument::REQUIRED,
                'The full path to the file to import. For example: `/absolute/path/to/data.csv`'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>=== Starting Data Import ===</info>');

        try {
            // Step 1: Read File using the supplied path
            $filePath = $input->getArgument('filePath');
            $aa = dirname(__FILE__);

            $output->writeln('<comment>Step 1: Validating and reading the file...</comment>');

            // File validation and reading handled by DataReaderService
            $data = $this->dataReaderService->getContentsFromFile($filePath);

            $this->logger->info(sprintf("File '%s' successfully validated and read.", $filePath));
            $output->writeln('<info>File successfully validated and contents read.</info>');

            // Step 2: Store data to the database
            $output->writeln('<comment>Step 2: Storing data into the database...</comment>');
            $this->dataWriterService->putContentsToDatabase($data);

            $this->logger->info(sprintf("Data from file '%s' successfully stored in the database.", $filePath));
            $output->writeln('<info>Data import completed successfully!</info>');
            $output->writeln('<info>=== Import Finished ===</info>');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $errorMessage = sprintf(
                'An error occurred during the data import process: %s',
                $exception->getMessage()
            );

            $this->logger->error($errorMessage, ['exception' => $exception]);

            $output->writeln('<error>' . $errorMessage . '</error>');
            $output->writeln('<error>Import process failed. Please check the logs for more details.</error>');

            return Command::FAILURE;
        }
    }
}
