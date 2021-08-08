<?php

function cleanFromEmptyArray(array $arr)
{
    $resault = [];

    foreach ($arr as $key => $value) {
        if (!empty($value)) {
            $resault[$key] = $value;
        }
    }

    return $resault;
}
