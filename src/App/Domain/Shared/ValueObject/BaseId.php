<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use InvalidArgumentException;
use Override;
use Stringable;

abstract readonly class BaseId implements Stringable
{
    final public function __construct(public string $value)
    {
        $this->assertValue($value);
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function equals(BaseId $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->value;
    }

    private function assertValue(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Invalid Id given');
        }
    }
}
