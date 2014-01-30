<?php
namespace Phine\Framework\System;

class ArrayList
{
    /**
     * Rotates the array, result begins with given index, items before are appended
     * @param array $array
     * @param int $startIndex 
     * @return Returns the rotated array
     */
    static function Rotate(array $array, $startIndex)
    {
        $startArray = array_slice($array, 0, $startIndex, true);
        $endArray = array_slice($array, $startIndex, null, true);
        //die('end array count:' . count($endArray));
        return array_merge($endArray, $startArray);
    }
}
