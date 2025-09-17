<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Parsing;

interface ParserInterface
{
    public function parse(array $row): object;
}