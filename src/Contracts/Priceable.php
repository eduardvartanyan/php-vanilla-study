<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Contracts;

interface Priceable
{
    public function getId(): string;
    public function getPrice(): float;
    public function getCurrency(): string;
    public function finalPrice(): float;
}