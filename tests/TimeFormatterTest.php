<?php declare(strict_types=1);

namespace nehlsen\TimeFormatter\Tests;

use nehlsen\TimeFormatter\TimeFormatter;
use nehlsen\TimeFormatter\TimeUnit;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

class TimeFormatterTest extends TestCase
{
    use ProphecyTrait;

    private TimeFormatter $formatter;

    protected function setUp(): void
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator
            ->trans(Argument::type('string'), Argument::type('array'))
            ->will(function (array $args) {
                $translationMessageId = $args[0];

                return sprintf('[trans]%s[/trans]', $translationMessageId);
            });

        $this->formatter = new TimeFormatter($translator->reveal());
    }

    #[Test]
    public function it_has_expected_default_options(): void
    {
        $seconds = 1337;

        $valueWithImplicitDefault = $this->formatter->format($seconds);
        self::assertSame('22 [trans]time.minutes.title[/trans] 17 [trans]time.seconds.title[/trans]', $valueWithImplicitDefault);

        $valueWithExplicitDefault = $this->formatter->format($seconds, [
            'significant' => 4,
            'abbreviate' => false,
            'fraction' => false,
            'precision' => 1,
            'fixed_unit' => TimeUnit::AUTOMATIC,
        ]);
        self::assertSame($valueWithExplicitDefault, $valueWithImplicitDefault);
    }

    #[Test]
    public function it_formats_fraction(): void
    {
        $seconds = 1337;

        $formatted = $this->formatter->format($seconds, ['fraction' => true, 'precision' => 1]);

        self::assertSame('22.3 [trans]time.minutes.title[/trans]', $formatted);
    }

    #[Test]
    public function it_rounds_fraction(): void
    {
        $seconds = 1337;

        $formatted = $this->formatter->format($seconds, ['fraction' => true, 'precision' => 0]);

        self::assertSame('22 [trans]time.minutes.title[/trans]', $formatted);
    }

    #[Test]
    public function it_rounds_not_only_fractions(): void
    {
        $seconds = 1337;

        $formatted = $this->formatter->format($seconds, ['fraction' => true, 'precision' => -1]);

        self::assertSame('20 [trans]time.minutes.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_in_fixed_unit_seconds(): void
    {
        $seconds = 1337;

        $formatted = $this->formatter->format($seconds, ['fraction' => true, 'fixed_unit' => TimeUnit::SECONDS]);

        self::assertSame('1337 [trans]time.seconds.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_in_fixed_unit_hours(): void
    {
        $seconds = 1337;

        $formatted = $this->formatter->format($seconds, ['fraction' => true, 'fixed_unit' => TimeUnit::HOURS]);

        self::assertSame('0.4 [trans]time.hours.title[/trans]', $formatted);
    }

    #[Test]
    public function it_automatically_formats_in_unit_seconds(): void
    {
        $seconds = 22;

        $formatted = $this->formatter->format($seconds, ['fraction' => true]);

        self::assertSame('22 [trans]time.seconds.title[/trans]', $formatted);
    }

    #[Test]
    public function it_automatically_formats_in_unit_minutes(): void
    {
        $seconds = 72;

        $formatted = $this->formatter->format($seconds, ['fraction' => true]);

        self::assertSame('1.2 [trans]time.minutes.title[/trans]', $formatted);
    }

    #[Test]
    public function it_automatically_formats_in_unit_hours(): void
    {
        $seconds = 3672;

        $formatted = $this->formatter->format($seconds, ['fraction' => true]);

        self::assertSame('1 [trans]time.hours.title[/trans]', $formatted);
    }

    #[Test]
    public function it_automatically_formats_in_unit_days(): void
    {
        $seconds = 86672;

        $formatted = $this->formatter->format($seconds, ['fraction' => true]);

        self::assertSame('1 [trans]time.days.title[/trans]', $formatted);
    }

    #[Test]
    public function it_properly_formats_a_big_number_using_default_options(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds);

        self::assertSame('5 [trans]time.days.title[/trans] 2 [trans]time.hours.title[/trans] 10 [trans]time.minutes.title[/trans] 37 [trans]time.seconds.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_into_four_significant_parts(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds, ['significant' => 4]);

        self::assertSame('5 [trans]time.days.title[/trans] 2 [trans]time.hours.title[/trans] 10 [trans]time.minutes.title[/trans] 37 [trans]time.seconds.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_into_three_significant_parts(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds, ['significant' => 3]);

        self::assertSame('5 [trans]time.days.title[/trans] 2 [trans]time.hours.title[/trans] 10 [trans]time.minutes.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_into_two_significant_parts(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds, ['significant' => 2]);

        self::assertSame('5 [trans]time.days.title[/trans] 2 [trans]time.hours.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_into_one_significant_part(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds, ['significant' => 1]);

        self::assertSame('5 [trans]time.days.title[/trans]', $formatted);
    }

    #[Test]
    public function it_formats_into_zero_significant_parts(): void
    {
        $seconds = 439837;

        $formatted = $this->formatter->format($seconds, ['significant' => 0]);

        self::assertSame('', $formatted);
    }
}
