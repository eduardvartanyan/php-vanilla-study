<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain\Auth;

use Eduardvartanan\PhpVanilla\Contracts\CsrfTokenManagerInterface;
use Random\RandomException;

final class CsrfTokenManager implements CsrfTokenManagerInterface
{
    private const string KEY = '_csrf';

    /**
     * @throws RandomException
     */
    public function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(16));
        }
        return $_SESSION[self::KEY];
    }

    /**
     * @throws RandomException
     */
    public function validate(string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $ok = hash_equals($_SESSION[self::KEY] ?? '', $token);
        if ($ok) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(16));
        }
        return $ok;
    }
}