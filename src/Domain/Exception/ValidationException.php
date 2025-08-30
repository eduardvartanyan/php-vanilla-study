<?php

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class ValidationException extends \RuntimeException
{
    public function __construct(public array $errors)
    {
        parent::__construct("Ошибка валидации:\n" . implode(";\n", $errors));
    }
}