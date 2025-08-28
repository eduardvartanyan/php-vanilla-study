<?php

namespace Eduardvartanan\PhpVanilla;

final class ValidationException extends \RuntimeException
{
    public function __construct(public array $errors)
    {
        parent::__construct("Ошибка валидации:\n" . implode(";\n", $errors));
    }
}