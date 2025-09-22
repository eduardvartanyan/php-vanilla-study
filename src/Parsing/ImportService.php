<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Parsing;

use Eduardvartanan\PhpVanilla\IO\CsvReader;
use Eduardvartanan\PhpVanilla\IO\JsonLinesReader;
use Eduardvartanan\PhpVanilla\IO\ReaderInterface;
use Eduardvartanan\PhpVanilla\Repository\ProductRepository;
use Eduardvartanan\PhpVanilla\Repository\UserRepository;
use Eduardvartanan\PhpVanilla\Support\Database;
use Eduardvartanan\PhpVanilla\Support\ImportResult;

final class ImportService
{
    public function import(ReaderInterface $reader, ParserInterface $parser): ImportResult
    {
        $result = new ImportResult();

        foreach ($reader->rows() as $line => $row) {
            try {
                $entity = $parser->parse($row);
                $result->incSuccess();
            } catch (\Throwable $e) {
                $result->addError($e, $line, $row, [
                    'entity' => $entity ?? null,
                ]);
            }
        }

        return $result;
    }

    /**
     * @throws \Throwable
     */
    public function importProducts(string $csvPath): ImportResult
    {
        $result = new ImportResult();
        $pdo    = Database::pdo();
        $repo   = new ProductRepository();
        $reader = new CsvReader($csvPath);
        $parser = new ProductParser();

        try {
            $pdo->beginTransaction();
            foreach ($reader->rows() as $i => $row) {
                $product = $parser->parse($row);
                $repo->create($product->getId(), $product->getName(), $product->getPrice(), $product->getCurrency());
                $result->incSuccess();
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * @throws \Throwable
     */
    public function importUsers(string $jsonlPath): ImportResult
    {
        $result = new ImportResult();
        $pdo    = Database::pdo();
        $repo   = new UserRepository();
        $reader = new JsonLinesReader($jsonlPath);
        $parser = new UserParser();

        try {
            $pdo->beginTransaction();
            foreach ($reader->rows() as $i => $row) {
                $user = $parser->parse($row);
                $repo->create($user->getName(), $user->getEmail(), $user->getAge());
                $result->incSuccess();
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        return $result;
    }
}