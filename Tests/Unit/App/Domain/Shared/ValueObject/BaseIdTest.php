<?php

declare(strict_types=1);

namespace Tests\Unit\App\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\BaseId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class BaseIdTest extends TestCase
{
    public function testWrongValueTrowAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Id given');
        $dto = new readonly class ('') extends BaseId {
        };

        self::fail('Did not throw exception: ' . $dto::class);
    }

    public function testEqualsReturnsTrueValue(): void
    {
        $dto = $this->getDto('SomeId');
        $sameId = $this->getDto('SomeId');
        $otherId = $this->getDto('OtherId');

        self::assertTrue($dto->equals($sameId));
        self::assertFalse($dto->equals($otherId));
    }

    public function testReturnValueAsString(): void
    {
        $dto = $this->getDto('SomeId');
        self::assertStringContainsString(
            'SomeId',
            (string) $dto,
        );
    }

    public function getDto(string $id): BaseId
    {
        return new readonly class ($id) extends BaseId {
        };
    }
}
