<?php

namespace App\Http\Helper;

use Cloudinary\Cloudinary;

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
    public static function upPDFToCloud($path)
    {
        $cloudinary = new Cloudinary(
            [
                'cloud' => [
                    'cloud_name' => env('CLOUD_NAME'),
                    'api_key'    => env('CLOUD_API_KEY'),
                    'api_secret' => env('CLOUD_API_SECRET'),
                ],
            ]
        );
      
        $path = $cloudinary->uploadApi()->upload($path);
        return json_decode(json_encode($path))->url;
    }
}
