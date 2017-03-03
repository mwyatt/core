<?php

namespace Mwyatt\Core\Factory;

class View extends \Mwyatt\Core\AbstractFactory
{
    protected $defaultNamespace = 'Mwyatt\\Core\\View\\';
    protected $defaultTemplateDirectory;


    public function __construct($defaultTemplateDirectory)
    {
        $this->defaultTemplateDirectory = $defaultTemplateDirectory;
    }


    public function get($name)
    {
        $namespace = $this->getDefaultNamespaceAbs($name);
        return new $namespace($this->defaultTemplateDirectory);
    }
}
