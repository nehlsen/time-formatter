# Time Formatter

Small Utility class to format _remaining_ time of something into a human-readable form.

## Example

Given `Milk` having a `bestBefore` Timestamp, we can calculate its remaining _lifetime_ like so

```php
/** @var Milk $myMilk */
$myMilk = $fridge->getMyMilk();
/** @var \DateTimeImmutable $bestBefore */
$bestBefore = $myMilk->getBestoBefore(); 

$secondsUntilBad = $bestBefore->getTimestamp() - time();

$formatter = new \nehlsen\TimeFormatter\TimeFormatter();
$formattedBestBefore = $formatter->format($secondsUntilBad);
// $formattedBestBefore == '3 Days 1 Hour 17 Seconds';
```

For a range of options and examples see [example1.php](example/example1.php)

## Installation

1. Require the library
   ```shell
   composer require nehlsen/time-formatter
   ```
2. For the translations to work, the provided files can be used, copied, linked.
   But they are not enabled automatically.
   * [messages.en.yaml](translation/messages.en.yaml)
   * [messages.de.yaml](translation/messages.de.yaml)
