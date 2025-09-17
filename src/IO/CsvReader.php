<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\IO;

final readonly class CsvReader implements ReaderInterface
{
    public function __construct(
        private string $path,
        private string $delimiter = ',')
    {
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
        $fh->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $fh->setCsvControl($this->delimiter, '"', '\\');

        $header = $fh->fgetcsv();
        if ($header === false || $header === [null]) {
            throw new \RuntimeException("Пустой или некорректный CSV: {$this->path}");
        }
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$header[0]);
        }

        while (!$fh->eof()) {
            $row = $fh->fgetcsv();
            if ($row === false || $row === [null]) { continue; }

            if (count($row) < count($header)) {
                $line = $fh->key() + 1;
                throw new \RuntimeException("Неполная строка CSV (ожидалось ".count($header).", получено ".count($row).") в строке {$line}");
            }

            $assoc = array_combine($header, array_slice($row, 0, count($header)));

            foreach ($assoc as $k => $v) {
                if (is_string($v)) {
                    $assoc[$k] = trim($v);
                }
            }

            yield ($fh->key() + 1) => $assoc;
        }
    }
}