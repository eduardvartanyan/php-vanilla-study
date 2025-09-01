<?php

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class AggregateImportException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Ошибка импорта");
    }
}