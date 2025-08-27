<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Eduardvartanan\PhpVanilla\User;

try {
    $user = new User('', 30, '');
} catch (Exception $e) {
    echo $e->getMessage();
}
