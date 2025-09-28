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
    $userId = new PdoSessionRepository(Database::pdo())->findUserByValidToken($_COOKIE['auth_token'], new DateTimeImmutable());
    $user = new UserRepository()->find($userId);
    echo 'Привет, ' . $user['name'] . '!';
} else {
?>
<form method="post" action="/login">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($token) ?>">
    <label>Email:
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>Пароль:
        <input type="password" name="password" placeholder="Пароль" required>
    </label>
    <label><input type="checkbox" name="remember"> Запомнить меня</label>
    <button type="submit">Войти</button>
</form>

<?php }
