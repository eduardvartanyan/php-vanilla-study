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

    public function register(string $email, string $plainPassword, string $name = '', int $age = 0): bool
    {
        if ($this->users->findByMail($email)) {
            throw new \RuntimeException('User already exists');
        }
        $hash = $this->hasher->hash($plainPassword);
        $userId = $this->users->create($email, $name, $age);
        return $this->users->savePasswordHash($userId, $hash);
    }
}