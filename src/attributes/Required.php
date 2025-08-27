<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)] // указывает, что атрибут может быть применен только к свойству класса
class Required
{
    private string $message;

    public function __construct(string $message = 'Поле обязательно для заполнения')
    {
        $this->message = $message;
    }

    public function validate(mixed $value): ?string
    {
        if (empty($value)) {
            return $this->message;
        }
        return null;
    }
}