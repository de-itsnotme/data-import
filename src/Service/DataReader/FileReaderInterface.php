<?php

declare(strict_types=1);

namespace App\Service\DataReader;

interface FileReaderInterface
{
    public function read(string $filePath): array;

    public function getSupportedExtension(): string;
}
