<?php

namespace Mwyatt\Core\Factory;

class Model extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\Model\\';


    public function get($name, array $data)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($data);
    }
}
