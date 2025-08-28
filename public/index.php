<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Eduardvartanan\PhpVanilla\User;
use Eduardvartanan\PhpVanilla\ValidationException;

try {
    $user = new User('Sam', 300, 'sam@mai.ru');
} catch (ValidationException $e) {
    echo $e->getMessage();
}
