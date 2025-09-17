<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class AggregateImportException extends \RuntimeException
{
    public function __construct($message)
    {
        parent::__construct('Ошибка импорта: ' . $message);
    }
}