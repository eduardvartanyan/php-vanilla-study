<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Currency implements Attribute
{
    public function __construct()
    {

    }

    public function validate(mixed $value, string $field): ?string
    {
        if (
            !is_string($value)
            || !preg_match('/^[A-Z]{3}$/', $value)
        ) {
            return "$field: Не корректное значение валюты";
        }
        return null;
    }
}
