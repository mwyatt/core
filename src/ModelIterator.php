<?php

namespace Mwyatt\Core;

class ModelIterator extends \ArrayIterator
{


    /**
     * key the iterator by the specified property
     * only 1 level deep
     * @param  string $property
     * @return array
     */
    public function getKeyedByProperty($property)
    {
        $keyed = [];
        foreach ($this as $object) {
            $keyed[$object->get($property)] = $object;
        }
        return $keyed;
    }


    /**
     * key the iterator by the specified property
     * but go 2 levels
     * @param  string $property
     * @return array
     */
    public function getKeyedByPropertyMulti($property)
    {
        $keyed = [];
        foreach ($this as $object) {
            if (empty($keyed[$object->get($property)])) {
                $keyed[$object->get($property)] = [];
            }
            $keyed[$object->get($property)][] = $object;
        }
        return $keyed;
    }


    /**
     * remove entries where property == value
     * @param  string $property
     * @param  mixed $value
     * @return null
     */
    public function filterOutByPropertyValue($property, $value)
    {
        foreach ($this as $key => $object) {
            if ($object->get($property) == $value) {
                $this->offsetUnset($key);
            }
        }
    }


    /**
     * get a collection of values from property
     * @param  string $property
     * @return array
     */
    public function extractProperty($property)
    {
        $collection = [];
        foreach ($this as $value) {
            $collection[] = $value->get($property);
        }
        return $collection;
    }


    /**
     * get the object which matches the property value
     * @param  string $property
     * @param  mixed $value
     * @return object
     */
    public function getByPropertyValue($property, $value)
    {
        $objects = [];
        foreach ($this as $object) {
            if ($object->get($property) == $value) {
                $objects[] = $object;
            }
        }
        return $objects;
    }


    public function append(\Mwyatt\Core\ModelInterface $model)
    {
        $this[] = $model;
    }
}
