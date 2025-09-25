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
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <label><input type="checkbox" name="remember"> Запомнить меня</label>
    <button type="submit">Войти</button>
</form>
