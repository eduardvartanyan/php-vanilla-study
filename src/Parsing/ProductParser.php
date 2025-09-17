<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Parsing;

use Eduardvartanan\PhpVanilla\Domain\Exception\ParseException;
use Eduardvartanan\PhpVanilla\Domain\Exception\ValidationException;
use Eduardvartanan\PhpVanilla\Domain\Product;

final class ProductParser implements ParserInterface
{
    /**
     * @param array<string,mixed> $row
     */
    public function parse(array $row): Product
    {
        try {
            return new Product(
                (string) ($row['id'] ?? ''),
                (string) ($row['name'] ?? ''),
                (float) ($row['price'] ?? 0.0),
                (string) ($row['currency'] ?? '')
            );
        } catch (ValidationException $e) {
            throw new ParseException('Некорректные данные товара. ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new ParseException('Ошибка при создании товара');
        }
    }
}