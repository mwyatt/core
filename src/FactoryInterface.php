<?php

namespace Mwyatt\Core;

interface FactoryInterface
{
    public function setDefaultNamespace($value);
    public function getDefaultNamespaceAbs($append = '');
}
