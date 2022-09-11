<?php

namespace App\Utils;

class PriceUtil
{
    public static function format(float $price): string
    {
        return number_format($price, 2, ',', '.');
    }
}
