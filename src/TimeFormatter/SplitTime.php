<?php declare(strict_types=1);

namespace nehlsen\TimeFormatterBundle\TimeFormatter;

/** @internal  */
readonly class SplitTime
{
    public function __construct(
        public int $seconds,
        public int $minutes,
        public int $hours,
        public int $days,
    ) {
    }

    public static function fromSeconds(int $seconds): self
    {
        if ($seconds < 0) {
            throw new \InvalidArgumentException('Negative values are not supported');
        }

        $minutes = 0;
        $hours = 0;
        $days = 0;

        if ($seconds >= 60) {
            $tmp = $seconds % 60;
            $minutes = ($seconds - $tmp) / 60;
            $seconds = $tmp;
        }
        if ($minutes >= 60) {
            $tmp = $minutes % 60;
            $hours = ($minutes - $tmp) / 60;
            $minutes = $tmp;
        }
        if ($hours >= 24) {
            $tmp = $hours % 24;
            $days = ($hours - $tmp) / 24;
            $hours = $tmp;
        }

        return new self(
            $seconds,
            (int) $minutes,
            (int) $hours,
            (int) $days,
        );
    }

    public function get(TimeUnit $unit): int
    {
        return match ($unit) {
            TimeUnit::SECONDS => $this->seconds,
            TimeUnit::MINUTES => $this->minutes,
            TimeUnit::HOURS => $this->hours,
            TimeUnit::DAYS => $this->days,

            default => throw new \InvalidArgumentException('Unsupported unit'),
        };
    }
}
