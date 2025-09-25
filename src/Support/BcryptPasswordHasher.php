<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Support;

use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }

    public function verify(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}