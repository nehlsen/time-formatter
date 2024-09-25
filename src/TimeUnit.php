<?php declare(strict_types=1);

namespace nehlsen\TimeFormatter;

enum TimeUnit: string
{
    case AUTOMATIC = 'automatic';

    case SECONDS = 'seconds';
    case MINUTES = 'minutes';
    case HOURS = 'hours';
    case DAYS = 'days';
}
