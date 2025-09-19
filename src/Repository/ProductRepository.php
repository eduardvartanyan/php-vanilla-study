<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Repository;

use PDO;
use Eduardvartanan\PhpVanilla\Support\Database;

final class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    /**
     * @return int — id добавленного пользователя
     */
    public function create(string $id, string $name, float $price, string $currency): string
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO products (id, name, price, currency) VALUES (:id, :name, :price, :currency);"
        );
        $stmt->execute([':id' => $id, ':name' => $name, ':price' => (int) $price * 100, ':currency' => $currency]);
        return $id;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id;");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function list(int $limit = 10, int $offset = 0): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY id LIMIT :limit OFFSET :offset;");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows ?: null;
    }

    public function update(int $id, array $data): bool
    {
        if (
            !array_key_exists('name', $data)
            || !array_key_exists('price', $data)
            || !array_key_exists('currency', $data)
        ) { return false; }

        $stmt = $this->pdo->prepare(
            "UPDATE products SET name = :name, price = :price, currency = :currency WHERE id = :id;"
        );

        return $stmt->execute([':id' => $id, ':name' => $data['name'],':price' => $data['price'], ':currency' => $data['currency']]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }
}