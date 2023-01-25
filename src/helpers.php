<?php

/**
 * distance
 *
 * @param  mixed $latitude1
 * @param  mixed $longitude1
 * @param  mixed $latitude2
 * @param  mixed $longitude2
 * @return float
 */
function distance(float $latitude1, float $longitude1, float $latitude2, float $longitude2) : float
{
    $p1 = deg2rad($latitude1);
    $p2 = deg2rad($latitude2);
    $dp = deg2rad($latitude2 - $latitude1);
    $dl = deg2rad($longitude2 - $longitude1);

    $a = (sin($dp/2) * sin($dp/2)) + (cos($p1) * cos($p2) * sin($dl/2) * sin($dl/2));
    $c = 2 * atan2(sqrt($a),sqrt(1-$a));
    $r = 6371.008; // Earth's average radius, in kilometers
    $d = $r * $c;
    return $d;
}