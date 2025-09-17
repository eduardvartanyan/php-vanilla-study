<?php

namespace Eduardvartanan\PhpVanilla\Parsing;

interface ParserInterface
{
    public function parse(array $row): object;
}