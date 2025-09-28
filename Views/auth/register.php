<?php

use Eduardvartanan\PhpVanilla\Domain\Auth\CsrfTokenManager;
use Random\RandomException;

try {
    $token = new CsrfTokenManager()->getToken();
} catch (RandomException $e) {
    $token = '';
}
?>
<form method="post" action="/login">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($token) ?>">
    <label>Имя:
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>Возраст:
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>Email:
        <input type="email" name="email" placeholder="Email" required>
    </label>
    <label>Пароль:
        <input type="password" name="password" placeholder="Пароль" required>
    </label>
    <button type="submit">Зарегистрироваться</button>
</form>
