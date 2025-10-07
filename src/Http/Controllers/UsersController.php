<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Http\Controllers;

use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\User;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;

final class UsersController
{
    public function __construct(private UserRepository $userRepository) { }

    private function json(mixed $data, int $status = 200, array $headers = []): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: no-referrer');
        foreach ($headers as $k => $v) {
            header("$k: $v");
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $size = min(100, max(1, (int) ($_GET['size'] ?? 20)));

        [$users, $total] = $this->userRepository->list($size, ($page - 1) * $size);

        $this->json([
            'data'  => $users,
            'total' => $total,
            'page'  => $page,
            'size'  => $size,
        ]);
    }

    public function store(): void
    {
        $raw = file_get_contents('php://input') ?: '{}';
        $data = json_decode($raw, true) ?? [];

        try {
            $newUser = new User(
                $data['name'] ?? '',
                (int) $data['age'] ?? 0,
                $data['email'] ?? '',
            );
            $this->userRepository->create($newUser);
        } catch (ValidationException $e) {
            $this->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function show(int $id): void
    {
        try {
            $user = $this->userRepository->find($id);
            if (!$user) {
                $this->json(['error' => 'Пользователь не найден'], 404);
                return;
            }
            $this->json(['data' => $user->toArray()]);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(int $id): void
    {
        try {
            $user = $this->userRepository->find($id);
            if (!$user) {
                $this->json(['error' => 'Пользователь не найден'], 404);
                return;
            }
            $raw = file_get_contents('php://input') ?: '{}';
            $data = json_decode($raw, true) ?? [];
            if (!$this->userRepository->update($id, $data)) {
                $this->json(['error' => 'Не удалось обновить пользователя'], 409);
            }
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }
}