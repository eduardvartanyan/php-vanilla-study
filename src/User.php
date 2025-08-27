<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla;

use Eduardvartanan\PhpVanilla\Attributes\Required;

/*
 * Создай класс User с типами свойств:
    string $name
    int $age
    string $email
 */

class User
{
    #[Required(message: 'Имя не может быть пустым')]
    private string $name;
    private int $age;
    private string $email;

    public function __construct(string $name, int $age, string $email)
    {
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;

        echo "Пользователь {$this->name} создан\n";
    }
}