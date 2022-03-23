<?php

namespace App\Http\Helper;

class Universal
{
    public static function formatNumber($number)
    {
        $number = floatval(str_replace(',', '', $number));
        $formatted = rtrim(rtrim(number_format($number, 4, ".", ""), '0'), '.');
        if (!strpos('.', $formatted)) {
            $formatted = number_format($formatted, 2);
        }
        return $formatted;
    }
    public static function linkPhoto($link)
    {

        if (str_starts_with($link, 'http')) {
            $photo = str_replace('http:', 'https:', $link);
            return asset($photo);
        }
        return $link;
    }
}
