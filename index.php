<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Eduardvartanan\PhpVanilla\Attributes\Required;
use Eduardvartanan\PhpVanilla\User;

$user = new User('', 30, 'john@example.com');

$ref = new ReflectionObject($user);
$refProps = $ref->getProperties();
foreach ($refProps as $prop) {
    /** @var string $value */
    $value = $prop->getValue($user);
    /** @var array $attrs */
    $attrs = $prop->getAttributes();
    /** @var ReflectionAttribute $attr */
    foreach ($attrs as $attr) {
        /** @var Required $instance */
        $instance = $attr->newInstance();
        /** @var ?string $error */
        $error = $instance->validate($value);
        if ($error) {
            echo "Ошибка: {$prop->getName()} — {$error}\n";
        }
    }
}
