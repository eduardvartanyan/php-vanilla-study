<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Traits;

use Eduardvartanan\PhpVanilla\Attributes\Percent;
use Eduardvartanan\PhpVanilla\ValidationException;
use Eduardvartanan\PhpVanilla\Validator;

trait AppliesDiscount
{
    #[Percent]
    private float $discountPercent = 0.0;

    public function setDiscountPercent(float $percent): void
    {
        $this->discountPercent = $percent;

        $errors = new Validator()->validate($this);
        if ($errors) {
            throw new ValidationException($errors);
        }
    }
    public function applyDiscount(float $amount): float
    {
        if ($amount > 0) {
            return (100 - $this->discountPercent) * $amount / 100;
        }
        return 0;
    }
}