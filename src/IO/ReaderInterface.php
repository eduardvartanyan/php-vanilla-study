<?php

namespace Eduardvartanan\PhpVanilla\IO;

interface ReaderInterface
{
    /** @return \Generator<int, array<string, mixed>> */
    public function rows(): \Generator;
}