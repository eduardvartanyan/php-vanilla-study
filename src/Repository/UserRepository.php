<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Repository;

use Eduardvartanan\PhpVanilla\Domain\User;
use Eduardvartanan\PhpVanilla\Support\Database;
use PDO;

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
    public function create(string $email, string $name, int $age, ?string $hash = null): int
    {
        if ($hash === null) {
            $hash = password_hash($email, PASSWORD_BCRYPT);
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, age, password_hash) VALUES (:name, :email, :age, :password_hash);"
        );
        $stmt->execute([
            ':name' => $name ?: $email,
            ':email' => $email,
            ':age' => $age ?: null,
            ':password_hash' => $hash,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @throws \Exception
     */
    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id;");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) { return null; }

        return new User(
            (string) $row['name'],
            (int) $row['age'],
            (string) $row['email'],
            (int) $row['id']
        );
    }

    /**
     * @throws \Exception
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email;");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) { return null; }

        return new User(
            (string) $row['name'],
            (int) $row['age'],
            (string) $row['email'],
            (int) $row['id']
        );
    }

    /** @return array{0: array|null, 1: int} */
    public function list(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("SELECT name, email, age FROM users ORDER BY id LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;

        $total = (int) $this->pdo->query("SELECT COUNT(*) FROM users;")->fetchColumn();

        return [$rows, $total];
    }

    public function getPasswordHash(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = :id;");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        if (!$row || !isset($row['password_hash'])) { return null; }
        return $row['password_hash'];
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
