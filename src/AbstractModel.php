<?php

namespace Mwyatt\Core;

abstract class AbstractModel implements \Mwyatt\Core\ModelInterface, \JsonSerializable
{
    protected $errors = [];


    /**
     * get property via method if in place or just the property
     * @param  string $property
     * @return mixed
     */
    public function __get($property)
    {
        $proposedMethod = 'get' . ucfirst($property);
        if (method_exists($this, $proposedMethod)) {
            return $this->$proposedMethod();
        }
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \Exception("Model property '$property' does not exist.");
    }


    /**
     * set via method or just property
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $proposedMethod = 'set' . ucfirst($property);
        if (method_exists($this, $proposedMethod)) {
            return $this->$proposedMethod($value);
        }
        if (property_exists($this, $property)) {
            return $this->$property = $value;
        }
        throw new \Exception("Model property '$property' does not exist.");
    }


    public function validate()
    {
        $this->errors = [];
        $this->validateRules();
        return $this->errors;
    }


    protected function validateRules()
    {
        // $this->validateProperty();
        // $this->validateProperty();
        // $this->validateProperty();
    }


    public function jsonSerialize()
    {
        return $this;
    }


    // legacy remove
    public function get($property)
    {
        return $this->__get($property);
    }
}
