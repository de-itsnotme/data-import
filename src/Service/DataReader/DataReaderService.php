<?php

declare(strict_types=1);

namespace App\Service\DataReader;

use App\Factory\Command\FileReaderFactory;
use RuntimeException;

class DataReaderService
{
    public function __construct(private FileReaderFactory $fileReaderFactory)
    {
    }

    public function getContentsFromFile(string $filePath): array
    {
        if (!$filePath || !file_exists($filePath)) {
            throw new RuntimeException(sprintf("The file '%s' could not be found or accessed.", $filePath));
        }

        $reader = $this->fileReaderFactory->getReader($filePath);

        return $reader->read($filePath);
    }
}
