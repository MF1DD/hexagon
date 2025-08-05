<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Adapter;

use App\Domain\Shared\Port\IdGeneratorInterface;
use Override;

final class FakeIdGenerator implements IdGeneratorInterface
{
    private int $counter = 1;

    #[Override]
    public function generate(): string
    {
        return sprintf('fake-id-%d', $this->counter++);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function reset(): void
    {
        $this->counter = 1;
    }
}
