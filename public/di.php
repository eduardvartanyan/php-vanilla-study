<?php
declare(strict_types=1);

use Eduardvartanan\PhpVanilla\Contracts\AuthStorageInterface;
use Eduardvartanan\PhpVanilla\Contracts\CacheInterface;
use Eduardvartanan\PhpVanilla\Contracts\CsrfTokenManagerInterface;
use Eduardvartanan\PhpVanilla\Contracts\PasswordHasherInterface;
use Eduardvartanan\PhpVanilla\Contracts\SessionRepositoryInterface;
use Eduardvartanan\PhpVanilla\Contracts\TokenGeneratorInterface;
use Eduardvartanan\PhpVanilla\Domain\Auth\CsrfTokenManager;
use Eduardvartanan\PhpVanilla\Http\Controllers\UsersController;
use Eduardvartanan\PhpVanilla\Infrastructure\Cache\RedisCacher;
use Eduardvartanan\PhpVanilla\Repository\PdoSessionRepository;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;
use Eduardvartanan\PhpVanilla\Support\BcryptPasswordHasher;
use Eduardvartanan\PhpVanilla\Support\Container;
use Eduardvartanan\PhpVanilla\Support\RandomTokenGenerator;
use Eduardvartanan\PhpVanilla\Support\SessionCookieStorage;

$container = new Container();
$container->set(PDO::class, fn() => new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]));
$container->set(AuthStorageInterface::class, fn() => new SessionCookieStorage());
$container->set(CsrfTokenManagerInterface::class, fn() => new CsrfTokenManager());
$container->set(PasswordHasherInterface::class, fn() => new BcryptPasswordHasher());
$container->set(SessionRepositoryInterface::class, fn($container) => new PdoSessionRepository($container->get(PDO::class)));
$container->set(TokenGeneratorInterface::class, fn() => new RandomTokenGenerator());
$container->set(UsersController::class, fn($container) => new UsersController($container->get(UserRepository::class)));
$container->set(CacheInterface::class, fn() => new RedisCacher());
