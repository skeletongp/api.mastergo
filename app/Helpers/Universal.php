<?php

use Cloudinary\Cloudinary;

function formatNumber($number)
{
    $number = floatval(str_replace(',', '', $number));
    $formatted = rtrim(rtrim(number_format($number, 4, ".", ""), '0'), '.');
    if (!strpos('.', $formatted)) {
        $formatted = number_format($formatted, 2);
    }
    return $formatted;
}
function linkPhoto($link)
{

    if (str_starts_with($link, 'http')) {
        $photo = str_replace('http:', 'https:', $link);
        return asset($photo);
    }
    return $link;
}
function upPDFToCloud($path)
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

/* @params Invoices $array, Related $key, Field $value */
/* Return Field´s Value from Related */
function arrayFind(array $array, $key, $value)
{
    $result = 0;
    foreach ($array as $ind => $item) {
        if ($array[$ind][$key] == $value) {
            $result = $item;
        }
    }
    return $result;
}
