<?php declare(strict_types=1);

use nehlsen\TimeFormatter\TimeFormatter;
use nehlsen\TimeFormatter\TimeUnit;
use Symfony\Contracts\Translation\TranslatorInterface;

include __DIR__ . '/../vendor/autoload.php';

class MockTranslator implements TranslatorInterface
{
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return match ($id) {
            'time.seconds.title' => 'Seconds',
            'time.seconds.abbr' => 's',
            'time.minutes.title' => 'Minutes',
            'time.minutes.abbr' => 'm',
            'time.hours.title' => 'Hours',
            'time.hours.abbr' => 'h',
            'time.days.title' => 'Days',
            'time.days.abbr' => 'd',

            default => $id,
        };
    }

    public function getLocale(): string
    {
        return 'fake';
    }
}

// ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----

$tomorrow = new DateTimeImmutable('next week 12:13:37');
$secondsUntilTomorrow = $tomorrow->getTimestamp() - time();

echo "TimeFormatter Example\n";
echo "Date: {$tomorrow->format(DATE_ATOM)}\n";
echo "remaining time:\n\n";

$formatter = new TimeFormatter(new MockTranslator());
echo $formatter->format($secondsUntilTomorrow) . "\n";
echo $formatter->format($secondsUntilTomorrow, ['abbreviate' => true]) . "\n";
echo $formatter->format($secondsUntilTomorrow, ['abbreviate' => true, 'significant' => 2]) . "\n";
echo $formatter->format($secondsUntilTomorrow, ['fraction' => true]) . "\n";
echo $formatter->format($secondsUntilTomorrow, ['fraction' => true, 'fixed_unit' => TimeUnit::HOURS]) . "\n";
