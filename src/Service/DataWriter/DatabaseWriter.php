<?php

declare(strict_types=1);

namespace App\Service\DataWriter;

use App\Entity\Product;
use App\Mapper\CsvDataMapper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DatabaseWriter
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private CsvDataMapper $csvDataMapper,
    ) {
    }

    public function putContentsToDatabase(array $rows): void
    {
        foreach ($rows as $row) {
            $dto = $this->csvDataMapper->map($row);

            $product = (new Product())
                ->setGtin($dto->gtin)
                ->setLanguage($dto->language)
                ->setTitle($dto->title)
                ->setPicture($dto->picture)
                ->setDescription($dto->description)
                ->setPrice((string) $dto->price)
                ->setStock($dto->stock)
            ;

            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();

        $this->logger->info(
            sprintf('%s rows inserted to database!', count($rows))
        );
    }
}
