<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Infrastructure\Cache;

use Eduardvartanan\PhpVanilla\Contracts\CacheInterface;
use Predis\Client;

class RedisCacher implements CacheInterface
{
    private object $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $key): mixed
    {
        return $this->client->get($key);
    }
    public function set(string $key, mixed $value): void
    {
        $this->client->set($key, $value, 'EX', 60);
    }
}
