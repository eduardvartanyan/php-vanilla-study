<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class MinLength
{
    private int $length;

    public function __construct(int $length = 0)
    {
        $this->length = $length;
    }

    public function validate(string $value): ?string
    {
        if (strlen($value) < $this->length) {
            return "Строка должна быть не менее {$this->length} символов";
        }
        return null;
    }
}