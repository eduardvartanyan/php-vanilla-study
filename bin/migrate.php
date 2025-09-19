#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Eduardvartanan\PhpVanilla\Support\Database;

$pdo = Database::pdo();
$dir = __DIR__ . '/../db/migrations';

foreach (glob($dir . '/*.sql') as $file) {
    echo '>> Running' . basename($file) . PHP_EOL;
    $pdo->exec(file_get_contents($file));
}
echo 'Done.' . PHP_EOL;
