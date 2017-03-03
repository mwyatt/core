<?php

namespace Mwyatt\Core;

interface CookieInterface
{
    public function get($key, $default = null);
    public function set($key, $value, $time);
    public function delete($key);
}
