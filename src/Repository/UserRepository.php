<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Repository;

use Eduardvartanan\PhpVanilla\Contracts\CacheInterface;
use Eduardvartanan\PhpVanilla\Domain\Exception\RepositoryException;
use Eduardvartanan\PhpVanilla\Domain\User;
use Eduardvartanan\PhpVanilla\Support\Database;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(private CacheInterface $cache)
    {
        $this->pdo = Database::pdo();
    }

    /**
     * @return int — id добавленного пользователя
     */
    public function create(User $newUser, ?string $hash = null): int
    {
        $email = $newUser->getEmail();

        if ($hash === null) {
            $hash = password_hash($email, PASSWORD_BCRYPT);
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (name, email, age, password_hash) VALUES (:name, :email, :age, :password_hash);"
            );
            $stmt->execute([
                ':name' => $newUser->getName() ?: $email,
                ':email' => $email,
                ':age' => $newUser->getAge() ?: null,
                ':password_hash' => $hash,
            ]);

            return (int) $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function find(int $id): ?User
    {
        $cachedUser = $this->cache->get("user:{$id}");
        if ($cachedUser) {
            return $this->hydrateUser(json_decode($cachedUser, true));
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id;");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            if (!$row) { return null; }

            $this->cache->set("user:{$id}", json_encode($row, JSON_UNESCAPED_UNICODE));
            return $this->hydrateUser($row);
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }


    public function findByEmail(string $email): ?User
    {
        $cachedUser = $this->cache->get("user:{$email}");
        if ($cachedUser) {
            return $this->hydrateUser(json_decode($cachedUser, true));
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email;");
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch();

            if (!$row) { return null; }

            $this->cache->set("user:{$email}", json_encode($row, JSON_UNESCAPED_UNICODE));

            return $this->hydrateUser($row);
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    /** @return array{0: array|null, 1: int} */
    public function list(int $limit = 10, int $offset = 0): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id, name, email, age FROM users ORDER BY id LIMIT :limit OFFSET :offset;");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;

            $total = (int) $this->pdo->query("SELECT COUNT(*) FROM users;")->fetchColumn();

            return [$rows, $total];
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function getPasswordHash(int $id): ?string
    {
        try {
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = :id;");
            $stmt->execute([':id'=>$id]);
            $row = $stmt->fetch();
            if (!$row || !isset($row['password_hash'])) { return null; }
            return $row['password_hash'];
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function touchLastLogin(int $id): void
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function update(int $id, array $data): bool
    {
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $age = $data['age'] ?? null;

        try {
            $stmt = $this->pdo->prepare("
            UPDATE users 
            SET 
                name = COALESCE(:name, name), 
                email = COALESCE(:email, email), 
                age = COALESCE(:age, age) 
            WHERE id = :id;");

            return $stmt->execute([':id' => $id, ':name' => $name,':email' => $email, ':age' => $age]);
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        if (!$id) { return false; }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    private function hydrateUser(array $row): User
    {
        return new User(
            (string) $row['name'],
            (int) $row['age'],
            (string) $row['email'],
            (int) $row['id'],
        );
    }
}
