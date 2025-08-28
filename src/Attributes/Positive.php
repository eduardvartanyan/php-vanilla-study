<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Positive implements Attribute
{
    public function __construct()
    {

    }

    public function validate(mixed $value, string $field): ?string
    {
        if (
            !is_int($value) && !is_float($value)
            || $value < 0
        ) {
            return "$field: Значение должно быть положительным";
        }
        return null;
    }
}