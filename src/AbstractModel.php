<?php

namespace Mwyatt\Core;

abstract class AbstractModel implements \Mwyatt\Core\ModelInterface
{


    /**
     * get property via method if in place or just the property
     * @param  string $property
     * @return mixed
     */
    public function get($property)
    {
        $proposedMethod = 'get' . ucfirst($property);

        if (method_exists($this, $proposedMethod)) {
            return $this->$proposedMethod();
        }

        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }


    protected function getAssertionChain($value)
    {
        return \Assert\that($value);
    }
}
