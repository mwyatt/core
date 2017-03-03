<?php

namespace Mwyatt\Core\Factory;

class Iterator extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Iterator\\';
    protected $contents = [];


    public function get($name)
    {
        try {
            $namespace = $this->getDefaultNamespaceAbs($name);
        } catch (\Exception $e) {
            $namespace = '\\Mwyatt\\Core\\Iterator\\' . $name;
            if (!class_exists($namespace)) {
                throw new \Exception("Iterator fallback '$namespace' does not exist.");
            }
        }
        return new $namespace($this->contents);
    }
}
