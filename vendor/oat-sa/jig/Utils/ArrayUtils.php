<?php

/**
 * This file is part of the Jig package.
 *
 * Copyright (c) 04-Mar-2013 Dieter Raber <me@dieterraber.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jig\Utils;

/**
 * ArrayUtils
 */
class ArrayUtils
{

    /**
     * Merges any number of arrays / parameters recursively, replacing
     * entries with string keys with values from latter arrays.
     * If the entry or the next value to be assigned is an array, then it
     * automagically treats both arguments as an array.
     * Numeric entries are appended, not replaced, but only if they are
     * unique
     *
     * @example result = array_merge_recursive_distinct(a1, a2, ... aN)
     * Credit: mark.roduner@gmail.com on http://php.net/manual/en/function.array-merge-recursive.php
     * */
    public static function arrayMergeRecursiveDistinct()
    {
        $arrays = func_get_args();
        $base   = array_shift($arrays);
        if (!is_array($base)) {
            $base = empty($base) ? array() : array($base);
        }

        foreach ($arrays as $append) {
            if (!is_array($append)) {
                $append = array($append);
            }

            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) and !is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) or is_array($base[$key])) {
                    $base[$key] = self::arrayMergeRecursiveDistinct($base[$key], $append[$key]);
                } else if (is_numeric($key)) {
                    if (!in_array($value, $base)) {
                        $base[] = $value;
                    }
                } else {
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }


    /**
     * Pick works very much like pop() or shift() but gets data from any key of the
     * array. It also works with associative keys
     *
     * @param array $array
     * @param string $wantedKey
     * @return array
     */
    public static function pick(array &$array, $wantedKey)
    {
        $returnVal = array();
        foreach ($array as $key => $value) {
            if ($key === $wantedKey) {
                $returnVal = $value;
            }
        }
        unset($array[$wantedKey]);
        return $returnVal;
    }

    /**
     * Quote all values of an array according to their type (mainly for usage in CSV)
     *
     * @param array $array
     * @return array
     */
    public static function csvQuote(array $array)
    {
        foreach ($array as &$value) {
            $value = is_array($value) ? self::csvQuote($value) : StringUtils::csvQuote($value);
        }
        return $array;
    }

    /**
     * Retrieve a - possibly nested - value from an array
     *
     * @param array $array
     * @param $key 'can.be.a.dot.separated.chain.of.nested.keys'
     * @return mixed|null
     */
    public static function getValue(array $array, $key) {
        if(isset($array[$key])) {
            return $array[$key];
        }
        else if(strpos($key, '.') !== null) {
            $current[0] =& $array;
            $i = 1;
            $key = explode('.', $key);
            $retVal = null;
            foreach($key as $value) {
                if(!isset($current[$i-1][$value])){
                    return null;
                }
                $retVal = $current[$i-1][$value];
                $current[$i] =& $current[$i-1][$value];
                $i++;
            }
            return $retVal;
        }
        return null;
    }

    /**
     * Adds data to an array
     *
     * @param string
     * @param mixed
     * @return bool
     *
     * @param $array
     * @param $key 'can.be.a.dot.separated.chain.of.nested.keys', an [] at the end writes in an array
     * @param $value of the variable to write
     * @return array
     */
    public static function setValue($array, $key, $value) {
        if(strpos($key, '[]') !== false) {
            $key = substr($key, 0, -2);
            $array[$key][] = $value;
            return $array;
        }
        if(strpos($key, '.') !== false) {
            $current[0] =& $array;
            $i = 1;
            foreach(explode('.', $key) as $part)  {
                if(!isset($current[$i-1][$part])) {
                    $current[$i-1][$part] = null;
                }
                $current[$i] =& $current[$i-1][$part];
                $i++;
            }
            //now set the value
            if(!empty($part)){
                $current[$i-2][$part] = $value;
            }
            return $array;
        }
        $array[$key] = $value;
        return $array;
    }
}
