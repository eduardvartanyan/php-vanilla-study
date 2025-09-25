<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

use DateTimeImmutable;

interface AuthStorageInterface
{
    public function setToken(string $token, DateTimeImmutable $expires): void;
    public function getToken(): ?string;
    public function clear(): void;
}