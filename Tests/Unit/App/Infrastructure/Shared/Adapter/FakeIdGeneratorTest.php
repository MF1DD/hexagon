<?php

declare(strict_types=1);

namespace Tests\Unit\App\Infrastructure\Shared\Adapter;

use App\Infrastructure\Shared\Adapter\FakeIdGenerator;
use PHPUnit\Framework\TestCase;

final class FakeIdGeneratorTest extends TestCase
{
    public function testGenerateFakeIds(): void
    {
        $dto = new FakeIdGenerator();

        self::assertSame('fake-id-1', $dto->generate());
        self::assertSame('fake-id-2', $dto->generate());
        self::assertSame('fake-id-3', $dto->generate());
    }

    public function testGenerateAndResetFaceIds(): void
    {
        $dto = new FakeIdGenerator();

        self::assertSame('fake-id-1', $dto->generate());
        self::assertSame('fake-id-2', $dto->generate());
        $dto->reset();
        self::assertSame('fake-id-1', $dto->generate());
        self::assertSame('fake-id-2', $dto->generate());
    }
}
