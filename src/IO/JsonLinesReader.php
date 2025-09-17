<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\IO;

use Eduardvartanan\PhpVanilla\Domain\Exception\ParseException;

final readonly class JsonLinesReader implements ReaderInterface
{
    public function __construct(
        private string $path
    ) {
        if (!is_file($this->path)) {
            throw new \RuntimeException("Файл не найден: {$this->path}");
        }
        if (!is_readable($this->path)) {
            throw new \RuntimeException("Файл не доступен для чтения: {$this->path}");
        }
    }

    /**
     * @return \Generator<int,array<string,mixed>> yields [lineNumber => rowAssoc]
     */
    public function rows(): \Generator
    {
        $fh = new \SplFileObject($this->path, 'r');
        $fh->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);

        $isFirst = true;

        foreach ($fh as $line) {
            $lineNumber = $fh->key() + 1;

            if ($line === false || $line === null) { continue; }

            if ($isFirst) {
                $line = (string)preg_replace('/^\xEF\xBB\xBF/', '', (string)$line);
                $isFirst = false;
            }

            if (!json_validate($line)) {
                throw new ParseException("Некорректная строка JSON в строке {$lineNumber}");
            }

            $data = json_decode($line, true);

            yield $lineNumber => $data;
        }
    }
}