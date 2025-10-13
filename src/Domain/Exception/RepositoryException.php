<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

class RepositoryException extends \RuntimeException
{
    public function __construct($message)
    {
        parent::__construct('Ошибка при работе с данными. ' . $message);
    }
}