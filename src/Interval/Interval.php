<?php

namespace Interval;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Interval\Exception\InvalidIntervalFormat;

class Interval
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var DateInterval
     */
    private $dateInterval;

    /**
     * @var bool
     */
    private $isAdd;

    /**
     * @var array
     */
    private $time;

    /**
     * @param $value
     * @return bool
     */
    public static function validate($value)
    {
        return (bool)preg_match('/^[\-]*(P.*)(\|\d+:\d+:\d+)*$/', $value);
    }

    /**
     * Interval constructor.
     * @param $intervalStr
     * @throws InvalidIntervalFormat
     */
    public function __construct($intervalStr)
    {
        if (!self::validate($intervalStr)) {
            throw new InvalidIntervalFormat("Invalid format: " . $intervalStr);
        }
        $this->value = $intervalStr;
        $this->parseInterval();
    }

    /**
     * @param string $intervalStr
     * @param $dateTime
     * @return DateTimeInterface|DateTimeImmutable|DateTime
     * @throws InvalidIntervalFormat
     */
    public static function getDateTimeFromInterval($intervalStr, $dateTime)
    {
        $interval = new Interval($intervalStr);
        return $interval->getDate($dateTime);
    }

    /**
     * @param DateTimeInterface|DateTimeImmutable|DateTime $dateTime
     * @return DateTimeInterface|DateTimeImmutable|DateTime
     */
    public function getDate($dateTime)
    {
        $clone = clone $dateTime;
        if ($this->isAdd) {
            $clone = $clone->add($this->dateInterval);
        } else {
            $clone = $clone->sub($this->dateInterval);
        }
        if ($this->time !== null) {
            $hour = array_key_exists(0, $this->time) ? $this->time[0] : (int)$clone->format('H');
            $minute = array_key_exists(1, $this->time) ? $this->time[1] : (int)$clone->format('i');
            $second = array_key_exists(2, $this->time) ? $this->time[2] : (int)$clone->format('s');
            $clone = $clone->setTime($hour, $minute, $second);
        }

        return $clone;
    }

    private function parseInterval()
    {
        $this->value = trim($this->value);
        $interval = explode('|', ltrim($this->value, '-'));

        // If first character is `-` then interval will be subtracted
        $this->isAdd = $this->value[0] !== '-';
        $this->dateInterval = new DateInterval($interval[0]);

        $this->time = null;
        if (array_key_exists(1, $interval)) {
            $time = $interval[1];
            $timeSplit = explode(':', $time);
            $this->time = $timeSplit;
        }
    }
}
