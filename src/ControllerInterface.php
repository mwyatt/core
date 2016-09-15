<?php

namespace Mwyatt\Core;

interface ControllerInterface
{
    public function redirect($key, $config = [], $statusCode = 200);
}
