<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Eduardvartanan\PhpVanilla\Contracts\AuthStorageInterface;
use Eduardvartanan\PhpVanilla\Contracts\CsrfTokenManagerInterface;
use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;
use Eduardvartanan\PhpVanilla\Contracts\SessionRepositoryInterface;
use Eduardvartanan\PhpVanilla\Contracts\TokenGeneratorInterface;
use Eduardvartanan\PhpVanilla\Domain\Auth\CsrfTokenManager;
use Eduardvartanan\PhpVanilla\Http\Controllers\AuthController;
use Eduardvartanan\PhpVanilla\Repository\PdoSessionRepository;
use Eduardvartanan\PhpVanilla\Support\BcryptPasswordHasher;
use Eduardvartanan\PhpVanilla\Support\Container;
use Eduardvartanan\PhpVanilla\Support\Database;
use Eduardvartanan\PhpVanilla\Support\RandomTokenGenerator;
use Eduardvartanan\PhpVanilla\Support\SessionCookieStorage;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$container = new Container();
$container->set(AuthStorageInterface::class, fn() => new SessionCookieStorage());
$container->set(CsrfTokenManagerInterface::class, fn() => new CsrfTokenManager());
$container->set(PasswordHasherInterface::class, fn() => new BcryptPasswordHasher());
$container->set(SessionRepositoryInterface::class, fn($container) => new PdoSessionRepository(Database::pdo()));
$container->set(TokenGeneratorInterface::class, fn() => new RandomTokenGenerator());

try {
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
