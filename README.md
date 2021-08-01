# Advanced DateIntervals

Package for more flexible date interval usage. `getDate` works with any `DateInterval` implementing class.

## Install

```text
composer require szymat/date-time-interval-advanced
```

## Format
```text
-P*|H:i:s

`-` => defines if period will be added or subtracted
`P*` => Standard PHP DateInterval format 
'|H:i:s' => (optional) `|` separator with fixed time to set 
```
[DateInterval Format](https://www.php.net/manual/en/dateinterval.format.php)

## Example

```php
// Can be also DateTime object
$date = new DateTimeImmutable('2021-05-12 13:43:10');
$interval = new Interval\Interval('-P1D|23:59:59');
$newDate = $interval->getDate($date); // Will return new object
echo $date->format('Y-m-d H:i:s') . ' => '.$newDate->format('Y-m-d H:i:s') . "\n";
```
Will output
```text
2021-05-12 13:43:10 => 2021-05-11 23:59:59
```

---

```php
$date = new DateTimeImmutable('2021-05-12 13:43:10');
$interval = new Interval\Interval('P15D|14:00');
$newDate = $interval->getDate($date);
echo $date->format('Y-m-d H:i:s') . ' => '.$newDate->format('Y-m-d H:i:s') . "\n";
```
Will output
```text
2021-05-12 13:43:10 => 2021-05-27 14:00:10
```

---

```php
$date = new DateTimeImmutable('2021-05-12 13:43:10');
$interval = new Interval\Interval('-P5D');
$newDate = $interval->getDate($date);
echo $date->format('Y-m-d H:i:s') . ' => '.$newDate->format('Y-m-d H:i:s') . "\n";
```
Will output
```text
2021-05-12 13:43:10 => 2021-05-07 13:43:10
```
