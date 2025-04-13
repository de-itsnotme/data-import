<?php

namespace App\Validator;

interface FieldsValidatorInterface
{
    public function validate(array $data): void;
}
