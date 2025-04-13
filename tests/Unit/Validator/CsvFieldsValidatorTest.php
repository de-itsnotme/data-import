<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Enum\LanguageEnum;
use App\Schema\CsvFieldSchema;
use App\Validator\CsvFieldsValidator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CsvFieldsValidatorTest extends TestCase
{
    public function testValidationHappyPath(): void
    {
        $testData = [
            CsvFieldSchema::FIELD_GTIN => '7034621736823',
            CsvFieldSchema::FIELD_LANGUAGE => 'en',
            CsvFieldSchema::FIELD_TITLE => 'Product 1',
            CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image1.jpg',
            CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 1.',
            CsvFieldSchema::FIELD_PRICE => 738.7,
            CsvFieldSchema::FIELD_STOCK => 100,
        ];

        $validator = new CsvFieldsValidator();
        $validator->validate($testData);

        $this->assertTrue(true);
    }

    public function testValidateShouldThrowExceptionWhenFieldsAreMissing(): void
    {
        $testData = [
            'foo' => '7034621736823',
            'bar' => 'en',
            CsvFieldSchema::FIELD_TITLE => 'Product 1',
            CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image1.jpg',
            CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 1.',
            CsvFieldSchema::FIELD_PRICE => 738.7,
            CsvFieldSchema::FIELD_STOCK => 100,
        ];

        $validator = new CsvFieldsValidator();

        try {
            $validator->validate($testData);
        } catch (\RuntimeException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $this->assertStringContainsString(CsvFieldSchema::FIELD_GTIN, $errorMessage);
        $this->assertStringContainsString(CsvFieldSchema::FIELD_LANGUAGE, $errorMessage);
    }

    public function testValidateInvalidLanguageShouldThrowException(): void
    {
        $testData = [
            CsvFieldSchema::FIELD_GTIN => '7034621736823',
            CsvFieldSchema::FIELD_LANGUAGE => 'aa',
            CsvFieldSchema::FIELD_TITLE => 'Product 1',
            CsvFieldSchema::FIELD_PICTURE => 'http://example.com/image1.jpg',
            CsvFieldSchema::FIELD_DESCRIPTION => 'This is the description for product 1.',
            CsvFieldSchema::FIELD_PRICE => 738.7,
            CsvFieldSchema::FIELD_STOCK => 100,
        ];

        $errorMessage = null;
        $validator = new CsvFieldsValidator();

        try {
            $validator->validate($testData);
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
        }

        $expectedInvalidLanguage = $testData[CsvFieldSchema::FIELD_LANGUAGE];
        $expectedAllowedLanguages = implode(', ', array_column(LanguageEnum::cases(), 'value'));

        $this->assertStringContainsString($expectedInvalidLanguage, $errorMessage);
        $this->assertStringContainsString($expectedAllowedLanguages, $errorMessage);
    }
}
