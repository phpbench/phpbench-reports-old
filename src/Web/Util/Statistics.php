<?php

namespace Phpbench\Reports\Util;

class Statistics
{
    public static function histogram(array $values, $steps = 10, $lowerBound = null, $upperBound = null)
    {
        $min = $lowerBound ?: min($values);
        $max = $upperBound ?: max($values);

        $range = $max - $min;

        $step = $range / $steps;
        $steps++; // add one extra step to catch the max value

        $histogram = [];

        $floor = $min;
        for ($i = 0; $i < $steps; $i++) {
            $ceil = $floor + $step;

            if (!isset($histogram[(string) $floor])) {
                $histogram[(string) $floor] = 0;
            }

            foreach ($values as $value) {
                if ($value >= $floor && $value < $ceil) {
                    $histogram[(string) $floor]++;
                }
            }

            $floor += $step;
            $ceil += $step;
        }

        return $histogram;
    }

    public static function rstdev(array $values, $sample = false)
    {
        $mean = self::mean($values);

        return $mean ? self::stdev($values) / $mean * 100 : 0;
    }

    public static function stdev(array $values, $sample = false)
    {
        $variance = self::variance($values, $sample);

        return \sqrt($variance);
    }

    public static function variance(array $values, $sample = false)
    {
        $average = self::mean($values);
        $sum = 0;
        foreach ($values as $value) {
            $diff = pow($value - $average, 2);
            $sum += $diff;
        }

        if (count($values) === 0) {
            return 0;
        }

        $variance = $sum / (count($values) - ($sample ? 1 : 0));

        return $variance;
    }

    public static function mean($values)
    {
        if (empty($values)) {
            return 0;
        }

        $sum = array_sum($values);

        if (0 == $sum) {
            return 0;
        }

        $count = count($values);

        return $sum / $count;
    }
}
