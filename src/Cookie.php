<?php

namespace Mwyatt\Core;

class Cookie implements \Mwyatt\Core\CookieInterface
{


    public function get($key, $default = null)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }


    /**
     * If output exists prior to calling this function, setcookie() will fail
     * and return FALSE. If setcookie() successfully runs, it will return
     * TRUE. This does not indicate whether the user accepted the cookie.
     *
     * updates the cookie if already set
     *
     * @param string $key
     * @param string $value
     * @param int $time  epoch
     */
    public function set($key, $value, $time)
    {
        if (isset($_COOKIE[$key])) {
            setcookie($key, $value);
        } else {
            setcookie($key, $value, $time);
        }
    }


    public function delete($key)
    {
        unset($_COOKIE[$key]);
    }
}
