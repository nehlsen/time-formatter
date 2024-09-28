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

## Usage options

The `TimeFormatter::format(...)` method accepts an array of options. The following Options are supported:

* `fraction` (`bool`)
    * whether to return a fraction or individual parts
    * fraction: `1.5h`
    * parts: `1h 30m`
* `significant` (`int`)
    * only applicable when `fraction` is `false`
    * it allows shortening the information a bit while introducing a little fuzziness
    * for example, a value of `2` could return `2 Days 3 Hours` instead of `2 Days 3 Hours 23 Minutes 48 Seconds`
* `abbreviate` (`bool`)
   * whether to abbreviate the time part units (e.g., 's' vs. 'Seconds', 'h' vs. 'Hours', etc.)
   * abbreviate: `1h 30m`
   * normal: `1 Hour 30 Minutes`
* `precision` (`int`)
   * only applicable when `fraction` is `true`
   * this option can be used to control the number of decimals used
   * for example, a precision of `3` could return `51.143 Hours` instead of `51.1 Hours`
   * negative precision is also supported to introduce some fuzziness to values
   * for example, a precision of `-1` could return `50 Hours` instead of `51.1 Hours`
* `fixed_unit` (`TimeUnit`)
   * only applicable when `fraction` is `true`
   * this option can be used to force a specific unit of time (see [TimeUnit](src/TimeFormatter/TimeUnit.php))
   * for example, a fixed unit of `TimeUnit::HOURS` could return `51.6 Hours` instead of `2.1 Days` 
