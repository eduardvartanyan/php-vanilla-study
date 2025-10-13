<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

interface CacheInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value): void;
}