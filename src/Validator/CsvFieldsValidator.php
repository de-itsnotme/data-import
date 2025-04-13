<?php

declare(strict_types=1);

namespace App\Validator;

use App\Enum\LanguageEnum;
use App\Schema\CsvFieldSchema;
use InvalidArgumentException;
use RuntimeException;

class CsvFieldsValidator implements FieldsValidatorInterface
{
    /** array<string, string> $data */
    public function validate(array $data): void
    {
        $fields = CsvFieldSchema::getFields();

        /** @var array<string, string> $missingFields */
        $missingFields = array_diff($fields, array_keys($data));

        $this->allFieldsArePresentValidator($missingFields);
        $this->allowedLanguageIsPresentValidator($data[CsvFieldSchema::FIELD_LANGUAGE]);
    }

    /**
     * @param array<string, string> $missingFields
     */
    public function allFieldsArePresentValidator(array $missingFields): void
    {
        if (count($missingFields) === 0) {
            return;
        }

        throw new RuntimeException(
            sprintf(
                'The following required fields are missing in the provided CSV: [%s]. Please ensure all necessary fields are included.',
                implode(', ', $missingFields)
            )
        );
    }

    public function allowedLanguageIsPresentValidator(string $value): void
    {
        if (LanguageEnum::tryFrom($value) !== null) {
            return;
        }

        $languageValues = array_column(LanguageEnum::cases(), 'value');

        throw new InvalidArgumentException(
            sprintf(
                'The provided language \'%s\' is not valid. Please use one of the following allowed languages: %s.',
                $value,
                implode(', ', $languageValues)
            )
        );
    }
}
