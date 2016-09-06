<?php

namespace Mwyatt\Core;

abstract class AbstractModel implements \Mwyatt\Core\ModelInterface
{


    /**
     * ensures that id cannot be set unless coming from db
     * sometimes a table will not use this
     * @param integer $id
     */
    public function __construct($id = 0)
    {
        if (property_exists($this, 'id') && method_exists($this, 'setId')) {
            $this->setId($id);
        }
    }


    protected function setId($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->integerish();
        return $this->id = $value;
    }


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


    /**
     * think about how this could be injected
     */
    protected function getAssertionChain($value)
    {
        return \Assert\that($value);
    }
}
