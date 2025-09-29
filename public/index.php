<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../public/di.php';

use Eduardvartanan\PhpVanilla\Http\Controllers\AuthController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    /** @var Container $container */
    $authController = $container->get(AuthController::class);

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($uri) {
        case '/login':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $authController->showLogin();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            }
            break;

        case '/register':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $authController->showRegister();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->register();
            }
            break;

        case '/logout':
            $authController->logout();
            break;

        case '/':
            echo 'Главная страница';
            break;

        default:
            http_response_code(404);
            echo '404 Страница не найдена';
    }
} catch (ReflectionException $e) {
    echo $e->getMessage();
}
