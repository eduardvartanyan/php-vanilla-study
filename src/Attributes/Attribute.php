<?php

namespace Eduardvartanan\PhpVanilla\Attributes;

interface Attribute
{
    public function validate(mixed $value, string $field): ?string;
}