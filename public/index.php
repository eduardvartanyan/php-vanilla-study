<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Eduardvartanan\PhpVanilla\Domain\Cart;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\Product;

try {
    $product = new Product('1', 'Стул', 8999, 'RUB');
    $product->setDiscountPercent(15);

    $cart = new Cart();
    $cart->addItem($product);
    $createdAt = $product->getCreatedAt()->format('Y-m-d H:i:s');
    $updatedAt = $product->getUpdatedAt()->format('Y-m-d H:i:s');
    $total = number_format($cart->total(), 2, '.', ' ');

    echo "Created: $createdAt
Updated: $updatedAt
Cart total: $total {$cart->currency()}";

} catch (ValidationException $e) {
    echo $e->getMessage();
}
