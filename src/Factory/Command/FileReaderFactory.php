<?php

declare(strict_types=1);

namespace App\Factory\Command;

use App\Service\DataReader\FileReaderInterface;
use InvalidArgumentException;

class FileReaderFactory
{
    /**
     * @var array <string, ReaderInterface>
     */
    private array $readers;

    public function __construct(iterable $readers)
    {
        foreach ($readers as $reader) {
            $this->readers[$reader->getSupportedExtension()] = $reader;
        }
    }

    public function getReader(string $filePath): FileReaderInterface
    {
        $extension = strtoupper(pathinfo($filePath, PATHINFO_EXTENSION));

        if (!isset($this->readers[$extension])) {
            throw new InvalidArgumentException(sprintf(
                    'The file type [%s] is not supported. Please provide a file in a supported format.',
                    $extension
                )
            );
        }

        return $this->readers[$extension];
    }
}
