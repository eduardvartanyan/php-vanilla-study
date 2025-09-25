<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

interface TokenGeneratorInterface
{
    public function generate(int $bytes = 32): string;
}