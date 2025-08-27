<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Email
{
    public function __construct()
    {

    }

    public function validate(string $value): ?string
    {
        $isValid = preg_match('/^[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}$/i', trim($value));
        if (!$isValid) {
            return 'Некорректный email';
        }
        return null;
    }
}