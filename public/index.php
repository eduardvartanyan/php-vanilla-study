<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../public/di.php';

use Eduardvartanan\PhpVanilla\Http\Controllers\AuthController;
use Eduardvartanan\PhpVanilla\Http\Controllers\UsersController;
use Eduardvartanan\PhpVanilla\Http\Middleware\AuthMiddleware;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    /** @var Container $container */
    $authController = $container->get(AuthController::class);
    $usersController = $container->get(UsersController::class);

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    switch ($uri) {
        case '/login':
            if ($method === 'GET') {
                $authController->showLogin();
            } elseif ($method === 'POST') {
                $authController->login();
            }
            break;

        case '/api/login':
            if ($method == 'POST') {
                $authController->loginApi();
            }
            break;

        case '/register':
            if ($method === 'GET') {
                $authController->showRegister();
            } elseif ($method === 'POST') {
                $authController->register();
            }
            break;

        case '/logout':
            $authController->logout();
            break;

        case '/':
            echo 'Главная страница';
            break;

        case '/users':
            AuthMiddleware::check();
            if ($method == 'GET') {
                $usersController->index();
            } elseif ($method == 'POST') {
                $usersController->store();
            }
            break;

        default:
            if (preg_match('#^/users/(\d+)$#', $uri, $m)) {
                AuthMiddleware::check();
                if ($method === 'GET') {
                    $usersController->show((int) $m[1]);
                } elseif ($method === 'PATCH') {
                    $usersController->update((int) $m[1]);
                } elseif ($method === 'DELETE') {
                    $usersController->destroy((int) $m[1]);
                }
            } else {
                http_response_code(404);
                echo '404 Страница не найдена';
            }
    }
} catch (ReflectionException $e) {
    echo $e->getMessage();
}
