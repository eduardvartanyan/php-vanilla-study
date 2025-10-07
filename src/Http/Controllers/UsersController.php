<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Http\Controllers;

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
            'data' => $users,
            'total' => $total,
            'page' => $page,
            'size' => $size,
        ]);
    }
}