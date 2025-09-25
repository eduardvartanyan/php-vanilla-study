<?php
declare(strict_types=1);

use Eduardvartanan\PhpVanilla\Contracts\CsrfTokenManagerInterface;
use Eduardvartanan\PhpVanilla\Domain\Auth\AuthService;
use Eduardvartanan\PhpVanilla\Domain\Auth\RegistrationService;

final class AuthController
{
    public function __construct(
        private readonly AuthService               $auth,
        private readonly RegistrationService       $reg,
        private readonly CsrfTokenManagerInterface $csrf
    ) {}

    public function showLogin(): void
    {
        $token = $this->csrf->getToken();
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
        echo 'Invalid credentials';
    }

    public function showRegister(): void
    {
        $token = $this->csrf->getToken();
        require __DIR__ . '/../../Views/auth/register.php';
    }

    public function register(): void
    {
        if (!$this->csrf->validate($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            echo 'CSRF failed';
            return;
        }
        $this->reg->register(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );
        header('Location: /login');
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /');
    }
}
