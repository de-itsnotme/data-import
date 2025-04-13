<?php

declare(strict_types=1);

namespace App\Schema;

class CsvFieldSchema
{
    public const FIELD_GTIN = 'gtin';
    public const FIELD_LANGUAGE = 'language';
    public const FIELD_TITLE = 'title';
    public const FIELD_PICTURE = 'picture';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_PRICE = 'price';
    public const FIELD_STOCK = 'stock';

    public static function getFields(): array
    {
        return [
            self::FIELD_GTIN,
            self::FIELD_LANGUAGE,
            self::FIELD_TITLE,
            self::FIELD_PICTURE,
            self::FIELD_DESCRIPTION,
            self::FIELD_PRICE,
            self::FIELD_STOCK,
        ];
    }
}
