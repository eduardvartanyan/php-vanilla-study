<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Attributes\Email;
use Eduardvartanan\PhpVanilla\Attributes\HumanAge;
use Eduardvartanan\PhpVanilla\Attributes\MinLength;
use Eduardvartanan\PhpVanilla\Attributes\Required;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Exception;

class User
{
    #[Required(message: 'Имя не может быть пустым')]
    #[MinLength(2)]
    private string $name;
    #[HumanAge]
    private int $age;
    #[Required(message: 'Email обязателен')]
    #[Email]
    private string $email;
    private int $id;

    /**
     * @throws ValidationException
     */
    public function __construct(string $name, int $age, string $email, ?int $id = 0)
    {
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
        $this->id = $id;

        $errors = new Validator()->validateObject($this);
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
    public function getId(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'age' => $this->age,
        ];
    }
}