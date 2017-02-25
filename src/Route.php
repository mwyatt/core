<?php

namespace Mwyatt\Core;

class Route extends \Mwyatt\Core\AbstractModel
{
    protected $type;
    protected $path;
    protected $controller;
    protected $method;
    protected $options = [];


    public function getOption($key)
    {
        return isset($this->options[$key]) ? $this->options[$key] : '';
    }
}
