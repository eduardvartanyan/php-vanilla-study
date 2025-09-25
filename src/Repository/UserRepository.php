<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Repository;

use PDO;
use Eduardvartanan\PhpVanilla\Support\Database;

final class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    /**
     * @return int — id добавленного пользователя
     */
    public function create(string $name, string $email, int $age): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, age) VALUES (:name, :email, :age);");
        $stmt->execute([
            ':name' => $name ?: $email,
            ':email' => $email,
            ':age' => $age ?: null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function savePasswordHash(int $id, string $hash): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET password_hash = :hash WHERE id = :id;"
        );
        return $stmt->execute([
            ':id' => $id,
            ':hash' => $hash,
        ]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id;");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByMail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email;");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function list(int $limit = 10, int $offset = 0): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY id LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows ?: null;
    }

    public function getPasswordHash(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function touchLastLogin(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public function update(int $id, array $data): bool
    {
        if (
            !array_key_exists('name', $data)
            || !array_key_exists('email', $data)
            || !array_key_exists('age', $data)
        ) { return false; }

        $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, age = :age WHERE id = :id;");

        return $stmt->execute([':id' => $id, ':name' => $data['name'],':email' => $data['email'], ':age' => $data['age']]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }
}
