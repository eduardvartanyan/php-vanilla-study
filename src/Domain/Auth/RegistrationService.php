<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Auth;

use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\User;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;

class RegistrationService
{
    public function __construct(
        private readonly UserRepository          $users,
        private readonly PasswordHasherInterface $hasher,
    ) {}

    /**
     * @throws \Exception
     */
    public function register(string $email, string $plainPassword, string $name = '', int $age = 0): int
    {
        if ($this->users->findByEmail($email)) {
            throw new \RuntimeException('Пользователь с таким email уже существует');
        }
        $hash = $this->hasher->hash($plainPassword);

        $newUser = new User($name, $age, $email);

        return $this->users->create($newUser, $hash);
    }
}