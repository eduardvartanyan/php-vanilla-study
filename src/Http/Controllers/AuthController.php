<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Http\Controllers;

use DateMalformedStringException;
use Eduardvartanan\PhpVanilla\Contracts\CsrfTokenManagerInterface;
use Eduardvartanan\PhpVanilla\Domain\Auth\AuthService;
use Eduardvartanan\PhpVanilla\Domain\Auth\RegistrationService;
use Firebase\JWT\JWT;

final readonly class AuthController
{
    public function __construct(
        private AuthService               $auth,
        private RegistrationService       $reg,
        private CsrfTokenManagerInterface $csrf
    ) {}

    public function showLogin(): void
    {
        $token = $this->csrf->getToken();
        http_response_code(200);
        require __DIR__ . '/../../Views/auth/login.php';
    }

    /**
     * @throws DateMalformedStringException
     */
    public function login(): void
    {
        if (!$this->csrf->validate($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            echo 'CSRF failed';
            return;
        }
        if ($this->auth->attempt(
            $_POST['email'] ?? '',
            $_POST['password'] ?? '',
            !empty($_POST['remember'])
        )) {
            header('Location: /');
            return;
        }
        http_response_code(401);
        echo 'Неверный логин или пароль';
    }

    /**
     * @throws DateMalformedStringException
     */
    public function loginApi(): void
    {
        if ($this->auth->attempt(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        )) {
            $payload = [
                'sub' => $this->auth->userId(),
                'email' => $_POST['email'],
                'iat' => time(),
                'exp' => time() + 60 * 60,
            ];
            $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

            http_response_code(200);
            echo json_encode(['token' => $jwt]);

            return;
        }
        http_response_code(401);
        echo 'Неверный логин или пароль';
    }

    public function showRegister(): void
    {
        $token = $this->csrf->getToken();
        http_response_code(200);
        require __DIR__ . '/../../Views/auth/register.php';
    }

    public function register(): void
    {
        if (!$this->csrf->validate($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            echo 'CSRF failed';
            return;
        }
        try {
            $this->reg->register(
                $_POST['email'] ?? '',
                $_POST['password'] ?? '',
                $_POST['name'] ?? '',
                (int) $_POST['age'] ?? 0,
            );
            header('Location: /login');
        } catch (\RuntimeException $e) {
            http_response_code(200);
            echo $e->getMessage();
        }
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /');
    }
}
