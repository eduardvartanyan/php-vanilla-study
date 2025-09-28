<?php

use Eduardvartanan\PhpVanilla\Domain\Auth\CsrfTokenManager;
use Eduardvartanan\PhpVanilla\Repository\PdoSessionRepository;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;
use Eduardvartanan\PhpVanilla\Support\Database;
use Random\RandomException;

try {
    $token = new CsrfTokenManager()->getToken();
} catch (RandomException $e) {
    $token = '';
}

if ($_COOKIE['auth_token']) {
    header('Location: /login');
} else {
?>
<form method="post" action="/register">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($token) ?>">
    <label>Имя:
        <input type="text" name="name" placeholder="Имя" required>
    </label>
    <label>Возраст:
        <input type="number" name="age" placeholder="Возраст" required>
    </label>
    <label>Email:
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>Пароль:
        <input type="password" name="password" placeholder="Пароль" required>
    </label>
    <button type="submit">Зарегистрироваться</button>
</form>

<?php }
