<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Attributes\Email;
use Eduardvartanan\PhpVanilla\Attributes\HumanAge;
use Eduardvartanan\PhpVanilla\Attributes\MinLength;
use Eduardvartanan\PhpVanilla\Attributes\Required;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;

class User
{
    #[Required(message: 'Имя не может быть пустым')]
    #[MinLength(2)]
    private string $name;
    #[HumanAge]
    private int $age;
    #[Email]
    private string $email;

    /**
     * @throws \Exception
     */
    public function __construct(string $name, int $age, string $email)
    {
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;

        $errors = new Validator()->validate($this);
        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getAge(): int
    {
        return $this->age;
    }
}