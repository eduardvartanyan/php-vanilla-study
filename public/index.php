<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\Product;
use Eduardvartanan\PhpVanilla\Domain\User;

try {
//    $user = new User('Sam', 300, 'sam@mai.ru');
    $product = new Product('1', 'Стул', 1000, 'RUB');
    var_dump($product);
} catch (ValidationException $e) {
    echo $e->getMessage();
}
