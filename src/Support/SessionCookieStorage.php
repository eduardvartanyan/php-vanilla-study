<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Support;

use Eduardvartanan\PhpVanilla\Contracts\AuthStorageInterface;

class SessionCookieStorage implements AuthStorageInterface
{
    private string $cookieName = 'auth_token';

    public function setToken(string $token, \DateTimeImmutable $expires): void
    {
        setcookie($this->cookieName, $token, [
            'expires'  => $expires->getTimestamp(),
            'path'     => '/',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[$this->cookieName] = $token;
    }
    public function getToken(): ?string
    {
        return $_COOKIE[$this->cookieName] ?? null;
    }
    public function clear(): void
    {
        setcookie($this->cookieName, '', time() - 3600, '/');
        unset($_COOKIE[$this->cookieName]);
    }
}