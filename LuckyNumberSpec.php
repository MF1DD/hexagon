<?php

declare(strict_types=1);

use App\Domain\LuckyNumber\Entity\LuckyNumber;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumber
 * @extends ObjectBehavior<int, LuckyNumber>
 */
final class LuckyNumberSpec extends ObjectBehavior
{
    public function __construct($object)
    {

    }
    public function let(): void
    {
        $this->beConstructedWith(14);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumber::class);
    }

    public function it_returns_the_number(): void
    {
        $this->number->shouldBe(144);
    }

    public function it_is_lucky_when_divisible_by_7(): void
    {
        $this->isLucky()->shouldBe(true);
    }

    public function it_is_not_lucky_when_not_divisible_by_7(): void
    {
        $this->beConstructedWith(13);
        $this->isLucky()->shouldBe(false);
    }

    public function it_throws_when_number_is_too_low(): void
    {
        $this->beConstructedWith(0);
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_throws_when_number_is_too_high(): void
    {
        $this->beConstructedWith(101);
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }
}
