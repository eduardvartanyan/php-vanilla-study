<?php
declare(strict_types=1);

use Eduardvartanan\PhpVanilla\Domain\Auth\AuthService;
use Eduardvartanan\PhpVanilla\Domain\Auth\CsrfTokenManager;
use Eduardvartanan\PhpVanilla\Domain\Auth\RegistrationService;
use Eduardvartanan\PhpVanilla\Http\Controllers\AuthController;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    protected $csrf;
    protected $auth;
    protected $reg;
    protected $controller;

    protected function setUp(): void
    {
        $this->auth = Mockery::mock(AuthService::class);
        $this->reg = Mockery::mock(RegistrationService::class);
        $this->csrf = Mockery::mock(CsrfTokenManager::class);
        $this->controller = new AuthController($this->auth, $this->reg, $this->csrf);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testShowLogin(): void
    {
        $_POST = ['_csrf' => 'goodtoken'];

        $this->csrf->shouldReceive('getToken')
            ->once()
            ->andReturn($_POST['_csrf']);

        $this->controller->showLogin();

        $this->assertEquals(200, http_response_code());
    }

    public function testLoginFailsWhenCsrfInvalid(): void
    {
        $_POST = ['_csrf' => 'badtoken'];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(false);

        ob_start();
        $this->controller->login();
        $output = ob_get_clean();

        $this->assertEquals(419, http_response_code());
        $this->assertStringContainsString('CSRF failed', $output);
    }

    public function testLoginSucceedsWhenCredentialsValid(): void
    {
        $_POST = [
            '_csrf' => 'goodtoken',
            'email' => 'test@mail.ru',
            'password' => 'password',
            'remember' => true,
        ];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(true);

        $this->auth->shouldReceive('attempt')
            ->once()
            ->with($_POST['email'], $_POST['password'], $_POST['remember'])
            ->andReturn(true);

        $this->expectOutputRegex('/^$/');
        $this->controller->login();

        $this->assertEquals(302, http_response_code());
    }

    public function testLoginFailsWhenCredentialsInvalid(): void
    {
        $_POST = [
            '_csrf' => 'goodtoken',
            'email' => 'test@mail.ru',
            'password' => 'password',
            'remember' => true,
        ];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(true);

        $this->auth->shouldReceive('attempt')
            ->once()
            ->with($_POST['email'], $_POST['password'], $_POST['remember'])
            ->andReturn(false);

        ob_start();
        $this->controller->login();
        $output = ob_get_clean();

        $this->assertEquals(401, http_response_code());
        $this->assertStringContainsString('Неверный логин или пароль', $output);
    }

    public function testShowRegister(): void
    {
        $_POST = ['_csrf' => 'goodtoken'];

        $this->csrf->shouldReceive('getToken')
            ->once()
            ->andReturn($_POST['_csrf']);

        $this->controller->showRegister();

        $this->assertEquals(200, http_response_code());
    }

    public function testRegisterFailsWhenCsrfInvalid(): void
    {
        $_POST = ['_csrf' => 'badtoken'];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(false);

        ob_start();
        $this->controller->register();
        $output = ob_get_clean();

        $this->assertEquals(419, http_response_code());
        $this->assertStringContainsString('CSRF failed', $output);
    }

    public function testRegisterSucceeds(): void
    {
        $_POST = [
            '_csrf' => 'goodtoken',
            'email' => 'test@mail.ru',
            'password' => 'password',
            'name' => 'Test',
            'age' => 35
        ];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(true);

        $this->reg->shouldReceive('register')
            ->once()
            ->with($_POST['email'], $_POST['password'], $_POST['name'], $_POST['age'])
            ->andReturn(true);

        $this->expectOutputRegex('/^$/');
        $this->controller->register();

        $this->assertEquals(302, http_response_code());
    }

    public function testRegisterFailsWhenUserExists(): void
    {
        $_POST = [
            '_csrf' => 'goodtoken',
            'email' => 'test@mail.ru',
            'password' => 'password',
            'name' => 'Test',
            'age' => 35
        ];

        $this->csrf->shouldReceive('validate')
            ->once()
            ->with($_POST['_csrf'])
            ->andReturn(true);

        $this->reg->shouldReceive('register')
            ->once()
            ->with($_POST['email'], $_POST['password'], $_POST['name'], $_POST['age'])
            ->andThrow(new \RuntimeException('Пользователь с таким email уже существует'));

        ob_start();
        $this->controller->register();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertStringContainsString('Пользователь с таким email уже существует', $output);
    }

    public function testLogout(): void
    {
        $this->auth->shouldReceive('logout')
            ->once()
            ->andReturnNull();

        $this->expectOutputRegex('/^$/');
        $this->controller->logout();

        $this->assertEquals(302, http_response_code(), 'Ожидается код ответа 302');
    }
}