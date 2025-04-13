<?php

declare(strict_types=1);

namespace App\Service\DataReader;

use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

class CsvFileReader implements FileReaderInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function read(string $filePath): array
    {
        $csvContent = file_get_contents($filePath);

        return $this->serializer->decode($csvContent, 'csv');
    }

    public function getSupportedExtension(): string
    {
        return 'CSV';
    }
}
