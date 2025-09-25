<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

interface CsrfTokenManagerInterface
{
    public function getToken(): string;
    public function validate(string $token): bool;
}