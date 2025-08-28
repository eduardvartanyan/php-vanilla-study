<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Traits;

trait HasTimestamps
{
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function initTimestamps(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }
    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}