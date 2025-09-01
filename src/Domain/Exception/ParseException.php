<?php

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class ParseException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Ошибка парсинга');
    }
}