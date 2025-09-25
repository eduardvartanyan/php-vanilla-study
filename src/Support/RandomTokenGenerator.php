<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Support;

use Eduardvartanan\PhpVanilla\Contracts\TokenGeneratorInterface;
use Random\RandomException;

class RandomTokenGenerator implements TokenGeneratorInterface
{
    /**
     * @throws RandomException
     */
    public function generate(int $bytes = 32): string
    {
        return bin2hex(random_bytes($bytes));
    }
}
