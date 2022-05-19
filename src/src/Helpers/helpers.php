<?php

/**
 * Generate a Random String
 *
 * @return string
 * @throws \Exception
 *
 *
 */
function random_string()
{
    $length = 8;
    $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZqwertyuiopasdfghjklzxcvbbnm,.<>()?1234567890:"';
    $random = substr(str_shuffle($charset),1,$length);;

    return $random;
}


/**
 *  Hash a String
 *
 * @param $data
 * @return false|string
 */
function string_hasher($data)
{
    $algo = 'sha256';
    $hashedString = hash($algo, $data);
    return $hashedString;
}


/**
 * Global date format
 *
 * @param $field
 * @return false|string
 */
function formattedDate($field) {
    return date_format(date_create($field),"d/m/Y");
}


function countFilledValues($array) {

    $count = 0;
    foreach ($array as $key => $value) {
        if (!empty($value)) {
            $count++;
        }
    }

    return $count;
}



/**
 * Generate a unique code
 *
 * @param $limit
 * @return bool|string
 */
function unique_code($limit) {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


function formatClassName($class) {


    $namespace = get_class($class);
    $exploded['name'] = explode('\\', $namespace)[2];

    $arr = preg_split('/(?=[A-Z])/', $exploded['name']);

    $str = str_replace(' ','_', strtolower($arr[1] . ' ' . $arr[2]));

    return $str;
}