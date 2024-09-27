<?php declare(strict_types=1);

namespace nehlsen\TimeFormatterBundle\Tests;

use nehlsen\TimeFormatterBundle\TimeFormatter\SplitTime;
use nehlsen\TimeFormatterBundle\TimeFormatter\TimeUnit;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SplitTimeTest extends TestCase
{
    use ProphecyTrait;

    #[Test]
    public function it_does_not_support_negative_values(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative values are not supported');

        SplitTime::fromSeconds(-1);
    }

    #[Test]
    public function it_splits_zero(): void
    {
        $split = SplitTime::fromSeconds(0);

        self::assertSame(0, $split->seconds);
        self::assertSame(0, $split->minutes);
        self::assertSame(0, $split->hours);
        self::assertSame(0, $split->days);
    }

    #[Test]
    public function it_detects_one_second(): void
    {
        $split = SplitTime::fromSeconds(1);

        self::assertSame(1, $split->seconds);
        self::assertSame(0, $split->minutes);
        self::assertSame(0, $split->hours);
        self::assertSame(0, $split->days);
    }

    #[Test]
    public function it_detects_one_minute(): void
    {
        $split = SplitTime::fromSeconds(60);

        self::assertSame(0, $split->seconds);
        self::assertSame(1, $split->minutes);
        self::assertSame(0, $split->hours);
        self::assertSame(0, $split->days);
    }

    #[Test]
    public function it_detects_one_hour(): void
    {
        $split = SplitTime::fromSeconds(60 * 60);

        self::assertSame(0, $split->seconds);
        self::assertSame(0, $split->minutes);
        self::assertSame(1, $split->hours);
        self::assertSame(0, $split->days);
    }

    #[Test]
    public function it_detects_one_days(): void
    {
        $split = SplitTime::fromSeconds(24 * 60 * 60);

        self::assertSame(0, $split->seconds);
        self::assertSame(0, $split->minutes);
        self::assertSame(0, $split->hours);
        self::assertSame(1, $split->days);
    }

    #[Test]
    public function it_splits_as_expected(): void
    {
        $split = SplitTime::fromSeconds(90061);

        self::assertSame(1, $split->seconds);
        self::assertSame(1, $split->minutes);
        self::assertSame(1, $split->hours);
        self::assertSame(1, $split->days);
    }

    #[Test]
    public function it_supports_usable_units_via_get(): void
    {
        $split = SplitTime::fromSeconds(90061);

        self::assertSame(1, $split->get(TimeUnit::SECONDS));
        self::assertSame(1, $split->get(TimeUnit::MINUTES));
        self::assertSame(1, $split->get(TimeUnit::HOURS));
        self::assertSame(1, $split->get(TimeUnit::HOURS));
    }

    #[Test]
    public function it_does_not_support_unusable_units_via_get(): void
    {
        $split = SplitTime::fromSeconds(90061);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported unit');

        $split->get(TimeUnit::AUTOMATIC);
    }
}
