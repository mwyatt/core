<?php

namespace Mwyatt\Core;

/**
 * commonly used and helpful functions
 * static because they should be
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Helper implements HelperInterface
{


    /**
     * check multiple array keys exist in an array
     * @param  array $keys
     * @param  array $array
     * @return bool
     */
    public static function arrayKeyExists($keys, $array)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return;
            }
        }
        return true;
    }


    /**
     * bats back a random string, good for unique codes
     * @param  integer $length how big is the code?
     * @return string
     */
    public static function getRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    

    /**
     * performs explode() on a string with the given delimiter
     * and trims all whitespace for the elements
     */
    public static function explodeTrim($str, $delimiter = ',')
    {
        if (is_string($delimiter)) {
            $str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str));
            return explode($delimiter, $str);
        }
        return $str;
    }


    /**
     * better than urlfriendly because & becomes 'amp' then when
     * making urls it can be translated?
     * @param  string $slug
     * @return string       foo-bar
     */
    public static function slugify($slug)
    {
        $slug = preg_replace('/\xE3\x80\x80/', ' ', $slug);
        $slug = str_replace('-', ' ', $slug);
        $slug = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $slug);
        $slug = str_replace('?', '', $slug);
        $slug = trim(mb_strtolower($slug, 'UTF-8'));
        $slug = preg_replace('#\x20+#', '-', $slug);
        return $slug;
    }


    /**
     * to add or not to add 's'
     * @param  array $group to count
     * @return string        's' | ''
     */
    public static function pluralise($group)
    {
        return count($group) > 1 ? 's' : '';
    }


    /**
     * calculates the average 0 to 100
     * @param  int $small
     * @param  int $big
     * @return int
     */
    public static function calcAverage($small, $big)
    {
        $average = 0;
        if ($big != 0) {
            $x = 0;
            $y = 0;
            $average = 0;
            $x = $small / $big;
            $y = $x * 100;
            // $average = round($y); // converts to 65%
            $average = number_format((float)$y, 2, '.', '');
        }
        return $average;
    }
}
