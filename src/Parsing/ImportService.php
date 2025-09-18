<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Parsing;

use Eduardvartanan\PhpVanilla\IO\ReaderInterface;
use Eduardvartanan\PhpVanilla\Support\ImportResult;

final class ImportService
{
    public function __construct(
        private ReaderInterface $reader,
        private ParserInterface $parser,
    ) { }

    public function import(): ImportResult
    {
        $result = new ImportResult();

        foreach ($this->reader->rows() as $line => $row) {
            try {
                $entity = $this->parser->parse($row);
                $result->incSuccess();
            } catch (\Throwable $e) {
                $result->addError($e, $line, $row, [
                    'entity' => $entity ?? null,
                ]);
            }
        }

        return $result;
    }
}