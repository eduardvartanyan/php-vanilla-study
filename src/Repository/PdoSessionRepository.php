<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Repository;

use Eduardvartanan\PhpVanilla\Contracts\SessionRepositoryInterface;
use PDO;

class PdoSessionRepository implements SessionRepositoryInterface
{
    public function __construct(private readonly PDO $pdo) {}
    public function create(int $userId, string $token, \DateTimeImmutable $expires, ?string $ip, ?string $ua): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO sessions (user_id, token, user_agent, ip, expires_at) 
                    VALUES (:user_id, :token, :user_agent, :ip, :expires_at);"
        );
        $stmt->execute([
            ':user_id'    => $userId,
            ':token'      => $token,
            ':expires_at' => $expires->format('Y-m-d H:i:s'),
            ':ip'         => $ip,
            ':user_agent' => $ua,
        ]);
    }
    public function findUserByValidToken(string $token, \DateTimeImmutable $now): ?int
    {
        $stmt = $this->pdo->prepare(
            "SELECT user_id FROM sessions WHERE token = :token AND expires_at > :expires_at LIMIT 1;"
        );
        $stmt->execute([
            ':token'      => $token,
            ':expires_at' => $now->format('Y-m-d H:i:s'),
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['user_id'] : null;
    }
    public function deleteByToken(string $token): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE token = :token");
        $stmt->execute([':token' => $token]);
    }
    public function deleteExpired(\DateTimeImmutable $now): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE expires_at <= :expires_at");
        $stmt->execute([':expires_at' => $now->format('Y-m-d H:i:s')]);
        return $stmt->rowCount();
    }
}