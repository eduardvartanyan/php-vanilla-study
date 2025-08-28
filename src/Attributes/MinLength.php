<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class MinLength implements Attribute
{
    private int $length;

    public function __construct(int $length = 0)
    {
        $this->length = $length;
    }

    public function validate(mixed $value, string $field): ?string
    {
        if (strlen($value) < $this->length) {
            return "$field: Строка должна быть не менее {$this->length} символов";
        }
        return null;
    }
}