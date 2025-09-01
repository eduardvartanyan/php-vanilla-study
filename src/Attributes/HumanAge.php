<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class HumanAge implements Attribute
{
    public function __construct()
    {

    }

    public function validate(mixed $value, string $field): ?string
    {
        if (
            !is_int($value)
            || ($value <= 0 || $value > 120)
        ) {
            return "$field: Некорректный возраст";
        }
        return null;
    }
}