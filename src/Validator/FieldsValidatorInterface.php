<?php

namespace App\Validator;

interface FieldsValidatorInterface
{
    /** @param array<string, string> $data */
    public function validate(array $data): void;
}
