<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla;

use Eduardvartanan\PhpVanilla\Attributes;

class User
{
    #[Attributes\Required(message: 'Имя не может быть пустым')]
    #[Attributes\MinLength(3)]
    private string $name;
    private int $age;
    #[Attributes\Email]
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
            $errorStr = implode(";\n", $errors);
            throw new \Exception("Ошибка валидации:\n$errorStr");
        }
    }
}