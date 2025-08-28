<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Percent implements Attribute
{
    public function __construct()
    {

    }

    public function validate(mixed $value, string $field): ?string
    {
        if (
            !is_float($value)
            || $value < 0
            || $value > 100
        ) {
            return "$field: Некорректное значение";
        }
        return null;
    }
}