<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Required implements Attribute
{
    private string $message;

    public function __construct(string $message = 'Поле обязательно для заполнения')
    {
        $this->message = $message;
    }

    public function validate(mixed $value, string $field): ?string
    {
        if (
            $value === null
            || (is_string($value) && trim($value) === '')
            || $value === []
        ) {
            return "$field: {$this->message}";
        }
        return null;
    }
}