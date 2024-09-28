# Time Formatter

Small Utility class to format seconds until or since into a human-readable form. For example, _"Task is due in 3 Days"_ or _"Milk is expired since 3 Hours 42 Minutes"_

## Example

Given `Milk` having a `bestBefore` Timestamp, we can calculate its remaining _lifetime_ like so

```php
/** @var Milk $myMilk */
$myMilk = $fridge->getMyMilk();
/** @var \DateTimeImmutable $bestBefore */
$bestBefore = $myMilk->getBestoBefore(); 

$secondsUntilBad = $bestBefore->getTimestamp() - time();

$formatter = new \nehlsen\TimeFormatterBundle\TimeFormatter\TimeFormatter();
$formattedBestBefore = $formatter->format($secondsUntilBad);
// $formattedBestBefore == '3 Days 1 Hour 17 Seconds';
```

For a range of options and examples, see [example2.php](example/example2.php) and [example1.php](example/example1.php)

## Installation

1. Require the library
   ```shell
   composer require nehlsen/time-formatter-bundle
   ```
