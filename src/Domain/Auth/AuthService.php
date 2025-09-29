<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Auth;

use DateMalformedStringException;
use DateTimeImmutable;
use Eduardvartanan\PhpVanilla\Contracts\AuthStorageInterface;
use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;
use Eduardvartanan\PhpVanilla\Contracts\SessionRepositoryInterface;
use Eduardvartanan\PhpVanilla\Contracts\TokenGeneratorInterface;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;

final readonly class AuthService
{
    public function __construct(
        private UserRepository             $users,
        private PasswordHasherInterface    $hasher,
        private SessionRepositoryInterface $sessions,
        private TokenGeneratorInterface    $tokens,
        private AuthStorageInterface       $storage
    ) {}

    /**
     * @throws DateMalformedStringException
     * @throws \Exception
     */
    public function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = $this->users->findByEmail($email);
        if (!$user) { return false; }

        $userId = $user->getId();
        $hash = $this->users->getPasswordHash($userId);
        if (!$this->hasher->verify($password, $hash)) { return false; }

        $ttl = $remember ? '+30 days' : '+2 hours';
        $expires = new DateTimeImmutable($ttl);
        $token = $this->tokens->generate();
        $this->sessions->create(
            $userId,
            $token,
            $expires,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        );
        $this->storage->setToken($token, $expires);

        $this->users->touchLastLogin($userId);

        return true;
    }

    public function userId(): ?int
    {
        $token = $this->storage->getToken();
        if (!$token) { return null; }
        return $this->sessions->findUserByValidToken($token, new DateTimeImmutable());
    }

    public function logout(): void
    {
        if ($token = $this->storage->getToken()) {
            $this->sessions->deleteByToken($token);
        }
        $this->storage->clear();
    }
}
