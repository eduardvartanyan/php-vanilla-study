<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Parsing;

use Eduardvartanan\PhpVanilla\Domain\Exception\ParseException;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\User;

final class UserParser implements ParserInterface
{
    /**
     * @param array<string,mixed> $row
     */
    public function parse(array $row): User
    {
        try {
            return new User(
                (string) ($row['name'] ?? ''),
                (int) ($row['age'] ?? 0),
                (string) ($row['email'] ?? '')
            );
        } catch (ValidationException $e) {
            throw new ParseException('Некорректные данные пользователя. ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new ParseException('Ошибка при создании пользователя');
        }
    }
}