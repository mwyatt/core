<?php

namespace Mwyatt\Core;

interface SessionInterface
{
    public function get($key, $default = null);
    public function set($key, $value);
    public function pull($key, $default = null);
}
