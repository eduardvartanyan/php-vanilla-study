<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Contracts\Priceable;

class Cart
{
    /** @var Priceable[] */
    private array $items = [];

    public function addItem(Priceable $item): void
    {
        $this->items[] = $item;
    }
    public function total(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total = $item->finalPrice();
        }
        return $total;
    }
    public function currency(): ?string
    {
        if ($this->items) {
            $currency = $this->items[0]->getCurrency();
            foreach ($this->items as $item) {
                if ($item->getCurrency() !== $currency) {
                    throw new \LogicException('All items in the cart must have the same currency');
                }
            }
            return $currency;
        }

        return null;
    }
}