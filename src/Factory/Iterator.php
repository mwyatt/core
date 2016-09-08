<?php

namespace Mwyatt\Core\Factory;

class Iterator extends \Mwyatt\Core\AbstractIterator
{


    protected $defaultNamespace = 'Mwyatt\\Core\\Iterator\\';
    protected $contents;


    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($this->contents);
    }
}
