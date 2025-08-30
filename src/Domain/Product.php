<?php



namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Attributes\Currency;
use Eduardvartanan\PhpVanilla\Attributes\MinLength;
use Eduardvartanan\PhpVanilla\Attributes\Positive;
use Eduardvartanan\PhpVanilla\Attributes\Required;
use Eduardvartanan\PhpVanilla\Contracts\Priceable;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Traits\AppliesDiscount;
use Eduardvartanan\PhpVanilla\Traits\HasTimestamps;

class Product implements Priceable
{
    use HasTimestamps;
    use AppliesDiscount;

    #[Required]
    private string $id;
    #[Required]
    #[MinLength(3)]
    private string $name;
    #[Required]
    #[Positive]
    private float $price;
    #[Required]
    #[Currency]
    private string $currency;

    public function __construct(string $id, string $name, float $price, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;

        $this->initTimestamps();

        $errors = new Validator()->validate($this);
        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function finalPrice(): float
    {
        return $this->applyDiscount($this->price);
    }

    public function withPrice(float $price): self
    {
        $clone = clone $this;
        $clone->price = $price;

        $errors = new Validator()->validate($clone);
        if ($errors) {
            throw new ValidationException($errors);
        }

        $clone->touch();
        return $clone;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        $errors = new Validator()->validate($clone);
        if ($errors) {
            throw new ValidationException($errors);
        }

        $clone->touch();
        return $clone;
    }
}
