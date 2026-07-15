<?php

namespace App\Support;

use Carbon\Carbon;

class BusinessHours
{
    protected static int $startHour = 9;
    protected static int $endHour = 18;

    // Sunday = 0, Monday = 1 ... Saturday = 6
    protected static array $workingDays = [1, 2, 3, 4, 5, 6];

    public static function diffInMinutes(Carbon $start, Carbon $end): int
    {
        if ($end->lessThanOrEqualTo($start)) {
            return 0;
        }

        $minutes = 0;
        $cursor = $start->copy();

        while ($cursor->lessThan($end)) {
            if (in_array($cursor->dayOfWeek, self::$workingDays)) {
                $dayStart = $cursor->copy()->setTime(self::$startHour, 0);
                $dayEnd = $cursor->copy()->setTime(self::$endHour, 0);

                $segmentStart = $cursor->greaterThan($dayStart) ? $cursor : $dayStart;
                $segmentEnd = $end->lessThan($dayEnd) ? $end : $dayEnd;

                if ($segmentStart->lessThan($segmentEnd) && $segmentStart->lessThan($dayEnd) && $segmentEnd->greaterThan($dayStart)) {
                    $minutes += $segmentStart->diffInMinutes($segmentEnd);
                }
            }

            $cursor->addDay()->startOfDay();
        }

        return $minutes;
    }

    public static function diffInHours(Carbon $start, Carbon $end): float
    {
        return round(self::diffInMinutes($start, $end) / 60, 2);
    }
}