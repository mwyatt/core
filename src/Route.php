<?php

namespace Mwyatt\Core;

class Route extends \Mwyatt\Core\AbstractModel
{
    protected $type;
    protected $path;
    protected $controller;
    protected $method;
    protected $options = [];
    protected $pathVars = [];


    public function getOption($key)
    {
        return isset($this->options[$key]) ? $this->options[$key] : '';
    }


    public function getPathVar($key)
    {
        return isset($this->pathVars[$key]) ? $this->pathVars[$key] : '';
    }


    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'path' => $this->path,
            'controller' => $this->controller,
            'method' => $this->method,
            'options' => $this->options,
            'pathVars' => $this->pathVars,
        ];
    }
}
