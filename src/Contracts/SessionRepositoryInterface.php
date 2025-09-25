<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

interface SessionRepositoryInterface
{
    public function create(int $userId, string $token, \DateTimeImmutable $expires, ?string $ip, ?string $ua): void;
    public function findUserByValidToken(string $token, \DateTimeImmutable $now): ?int;
    public function deleteByToken(string $token): void;
    public function deleteExpired(\DateTimeImmutable $now): void;
}