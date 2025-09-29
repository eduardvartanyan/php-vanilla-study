<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Auth;

use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;

final class RegistrationService
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasherInterface $hasher,
    ) {}

    public function register(string $email, string $plainPassword, string $name = '', int $age = 0): int
    {
        if ($this->users->findByEmail($email)) {
            throw new \RuntimeException('Пользователь с таким email уже существует');
        }
        $hash = $this->hasher->hash($plainPassword);
        return $this->users->create($email, $name, $age, $hash);
    }
}