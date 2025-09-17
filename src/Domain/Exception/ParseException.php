<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class ParseException extends \RuntimeException
{
    public function __construct($message)
    {
        parent::__construct('Ошибка парсинга: ' . $message);
    }
}