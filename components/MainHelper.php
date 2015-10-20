<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11.09.15
 * Time: 10:05
 */

namespace app\components;

class MainHelper {

    static function findInArray($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, self::findInArray($subarray, $key, $value));
            }
        }

        return $results;
    }
}