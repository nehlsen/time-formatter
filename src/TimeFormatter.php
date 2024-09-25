<?php declare(strict_types=1);

namespace nehlsen\TimeFormatter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TimeFormatter
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    protected function getOptionsResolver(): OptionsResolver
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setDefaults([
                // how many significant parts should be returned. e.g. 5d12h (significant 2) 5d12h23m (significant 3) ! ignored if fraction=true
                'significant' => 4,
                // whether or not to abbreviate the time part units (e.g. 's' vs. 'seconds', 'h' vs. 'hours', etc)
                'abbreviate' => false,

                // display a fraction instead of split values for d/h/m/s. e.g. 5d12h = 5.5d
                'fraction' => false,
                // when displaying a fraction round to this number of decimals
                'precision' => 1,
                // when displaying a fraction force to this unit
                'fixed_unit' => TimeUnit::AUTOMATIC,
            ])
            ->setAllowedTypes('significant', 'int')
            ->setAllowedTypes('abbreviate', 'bool')
            ->setAllowedTypes('fraction', 'bool')
            ->setAllowedTypes('precision', 'int')
            ->setAllowedTypes('fixed_unit', TimeUnit::class);

        return $optionsResolver;
    }

    /**
     * format seconds to a human-readable form.
     *
     * e.g.
     *  * 129600s => 1 day 12 hours  (default)
     *  * 129600s => 1d 12h          (abbreviate)
     *  * 129600s => 1.5d            (fraction)
     *  * 129600s => 2d              (fraction + round)
     *
     * @param array<string, bool|int|TimeUnit> $options
     */
    public function format(int $seconds, array $options = []): string
    {
        $options = $this->getOptionsResolver()->resolve($options);

        if ($options['fraction']) {
            return $this->formatFraction(
                $seconds,
                $options['precision'],
                $options['fixed_unit'],
                $options['abbreviate'],
            );
        }

        return $this->formatParts($seconds, $options['significant'], $options['abbreviate']);
    }

    protected function formatFraction(int $seconds, int $precision, TimeUnit $fixedUnit, bool $abbreviate): string
    {
        $unit = TimeUnit::SECONDS;

        if (TimeUnit::AUTOMATIC === $fixedUnit) {
            // determine the best unit
            if ($seconds >= 60) {
                $unit = TimeUnit::MINUTES;
            }
            if ($seconds >= 60 * 60) {
                $unit = TimeUnit::HOURS;
            }
            if ($seconds >= 60 * 60 * 24) {
                $unit = TimeUnit::DAYS;
            }
        } else {
            $unit = $fixedUnit;
        }

        $divisor = match ($unit) {
            TimeUnit::DAYS => 60 * 60 * 24,
            TimeUnit::HOURS => 60 * 60,
            TimeUnit::MINUTES => 60,
            default => 1,
        };

        $value = (1.0 * $seconds) / $divisor;
        if ($precision < 0) {
            $roundingFactor = 1;
            $rest = $precision * -1;
            while ($rest > 0) {
                $roundingFactor *= 10;
                --$rest;
            }
            $valueRounded = round($value / $roundingFactor, 0);
            $valueRounded *= $roundingFactor;

            $value = 0 == $valueRounded ? $value : $valueRounded;
        } else {
            $value = round($value, $precision);
        }

        $label = sprintf(
            'time.%s.%s',
            $unit->value,
            $abbreviate ? 'abbr' : 'title',
        );

        return sprintf(
            '%s %s',
            $value,
            $this->translator->trans($label, ['%count%' => $value]),
        );
    }

    protected function formatParts(int $seconds, int $significant, bool $abbreviate): string
    {
        $split = SplitTime::fromSeconds($seconds);
        $stringParts = [];
        foreach ([TimeUnit::DAYS, TimeUnit::HOURS, TimeUnit::MINUTES, TimeUnit::SECONDS] as $timeUnit) {
            $partValue = $split->get($timeUnit);
            if ($partValue < 1) {
                continue;
            }

            $partLabel = sprintf(
                'time.%s.%s',
                $timeUnit->value,
                $abbreviate ? 'abbr' : 'title',
            );
            $stringParts[] = sprintf(
                '%d%s%s',
                $partValue,
                $abbreviate ? '' : ' ',
                $this->translator->trans($partLabel, ['%count%' => $partValue]),
            );
        }

        $stringParts = array_slice($stringParts, 0, $significant);

        return implode(' ', $stringParts);
    }
}
