<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    static public function check(): void
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Осутствует токен'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Токен не прошел проверку'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
