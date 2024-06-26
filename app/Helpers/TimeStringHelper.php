<?php

namespace App\Helpers;

class TimeStringHelper
{
    public static function convertSecondsToTimeString($seconds)
    {
        $result = trans('i18n.expires.soon');

        if ($seconds < 30) {
            $result = trans('i18n.expires.in_a_few_seconds');
        } else if ($seconds < 61) {
            $result = trans('i18n.expires.in_less_than_a_minute');
        } else if ($seconds < 3601) {
            $minutes = intval($seconds / 60);
            $result = trans_choice('i18n.expires.in_minutes', $minutes, ['value' => $minutes]);
        } else if ($seconds < 86401) {
            $hours = intval($seconds / 3600);
            $result = trans_choice('i18n.expires.in_hours', $hours, ['value' => $hours]);
        } else {
            $days = intval($seconds / 86400);
            $result = trans_choice('i18n.expires.in_days', $days, ['value' => $days]);
        }

        return $result;
    }
}
