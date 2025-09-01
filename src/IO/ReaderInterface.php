<?php

namespace Eduardvartanan\PhpVanilla\IO;

interface ReaderInterface
{
    public function rows(): \Generator;
}