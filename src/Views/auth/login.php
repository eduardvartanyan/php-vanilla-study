<?php

use Eduardvartanan\PhpVanilla\Repository\PdoSessionRepository;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;
use Eduardvartanan\PhpVanilla\Support\Database;

if (isset($_COOKIE['auth_token'])) {
    $userId = new PdoSessionRepository(Database::pdo())->findUserByValidToken($_COOKIE['auth_token'], new DateTimeImmutable());

    if (!$userId) {
        Header('Location: /logout');
    }

    $userName = '';
    try {
        $user = new UserRepository()->find($userId);
        $userName = $user->getName();
    } catch (Exception $e) {
        $userName = 'уважаемый';
    }
    echo 'Привет, ' . $userName . '!';
} else {
    /** @var string $token */
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
