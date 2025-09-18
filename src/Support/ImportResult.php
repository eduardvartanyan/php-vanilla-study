<?php

namespace Eduardvartanan\PhpVanilla\Support;

final class ImportResult
{
    private int $successCount = 0;
    private array $errors = [];
    private array $errorsByType = [];

    private function buildChain(\Throwable $e): array
    {
        $chain = [];
        for ($cur = $e; $cur; $cur = $cur->getPrevious()) {
            $msg = $cur->getMessage();
            $chain = ($cur::class) . ': ' . ($msg !== '' ? $msg : '(no message)');
        }
        return $chain;
    }
    public function addError(\Throwable $e, int $line, ?array $payload = null, array $context = []): void
    {
        $entry = [
            'line' => $line,
            'type' => $e::class,
            'message' => $e->getMessage(),
            'chain' => $this->buildChain($e),
            'payload' => $payload,
            'code' => $e->getCode(),
            'time' => microtime(true),
            'context' => $context,
        ];

        $this->errors[] = $entry;
    }

    public function incSuccess(): void
    {
        $this->successCount++;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrorCount(): int
    {
        return count($this->errors);
    }
}