<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Traits;

use Eduardvartanan\PhpVanilla\Attributes\Percent;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\Validator;

trait AppliesDiscount
{
    use HasTimestamps;

    #[Percent]
    private float $discountPercent = 0.0;

    public function setDiscountPercent(float $percent): void
    {
        $this->discountPercent = $percent;

        $errors = new Validator()->validate($this);
        if ($errors) {
            throw new ValidationException($errors);
        }

        $this->touch();
    }
    public function applyDiscount(float $amount): float
    {
        return max(0.0, (100.0 - $this->discountPercent) * $amount / 100.0);
    }
}