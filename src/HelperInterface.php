<?php

namespace Mwyatt\Core;

/**
 * commonly used and helpful functions
 * static because they should be
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface HelperInterface
{


    /**
     * check multiple array keys exist in an array
     * @param  array $keys
     * @param  array $array
     * @return bool
     */
    public static function arrayKeyExists($keys, $array);


    /**
     * bats back a random string, good for unique codes
     * @param  integer $length how big is the code?
     * @return string
     */
    public static function getRandomString($length = 10);
    

    /**
     * performs explode() on a string with the given delimiter
     * and trims all whitespace for the elements
     */
    public static function explodeTrim($str, $delimiter = ',');


    /**
     * better than urlfriendly because & becomes 'amp' then when
     * making urls it can be translated?
     * @param  string $slug
     * @return string       foo-bar
     */
    public static function slugify($slug);


    /**
     * to add or not to add 's'
     * @param  array $group to count
     * @return string        's' | ''
     */
    public static function pluralise($group);


    /**
     * calculates the average 0 to 100
     * @param  int $small
     * @param  int $big
     * @return int
     */
    public static function calcAverage($small, $big);
}
