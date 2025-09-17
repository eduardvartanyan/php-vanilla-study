<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Exception;

final class DuplicateEntityException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Дублируются строки в исходной файле');
    }
}