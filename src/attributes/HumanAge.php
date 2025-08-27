<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class HumanAge
{
    public function __construct()
    {

    }

    public function validate(int $value): ?string
    {
        if ($value <= 0 || $value > 150) {
            return 'Некорректный возраст';
        }
        return null;
    }
}