<?php

namespace Mwyatt\Core;

abstract class AbstractFactory
{
    protected $defaultNamespace;


    protected function getDefaultNamespace($append = '')
    {
        return $this->defaultNamespace . $append;
    }


    protected function getDefaultNamespaceAbs($append = '')
    {
        $namespace = '\\' . $this->getDefaultNamespace($append);
        if (!class_exists($namespace)) {
            throw new \Exception("'$namespace' does not exist.");
        }
        return $namespace;
    }
}
