<?php

namespace Mwyatt\Core\Iterator;

class Model extends \Mwyatt\Core\AbstractIterator implements \Mwyatt\Core\Iterator\ModelInterface
{


    /**
     * often iterators are looped through but they are only
     * rewinded once the next loop begins, this will force a rewind
     * when occasionally trying to access the first item
     * @return object|null Model
     */
    public function getFirst()
    {
        $this->rewind();
        return $this->current();
    }


    /**
     * lazy extract
     * @return array
     */
    public function getIds()
    {
        return $this->extractPropertyUnique('id');
    }

    
    /**
     * lazy for getbypropertyvalue
     * @param  int $id
     * @return object
     */
    public function getById($id)
    {
        if ($models = $this->getByPropertyValues('id', [$id])) {
            return $models->getFirst();
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
        foreach ($this as $model) {
            $collection[] = $model->$property;
        }
        return $collection;
    }


    /**
     * get a collection of values from property unique
     * @param  string $property
     * @return array
     */
    public function extractPropertyUnique($property)
    {
        $collection = [];
        $uniqueRecord = [];
        foreach ($this as $model) {
            $propertyVal = $model->$property;
            if (!in_array($propertyVal, $uniqueRecord)) {
                $collection[] = $propertyVal;
            }
            $uniqueRecord[] = $propertyVal;
        }
        return $collection;
    }


    /**
     * get the models which match the property values
     * @param  string $property
     * @param  mixed $value
     * @return object ModelIterator
     */
    public function getByPropertyValues($property, array $values)
    {
        $models = [];
        foreach ($this as $model) {
            foreach ($values as $value) {
                if ($model->$property == $value) {
                    $models[] = $model;
                }
            }
        }
        return new $this($models);
    }


    /**
     * key the iterator by the specified property
     * only 1 level deep
     * @param  string $property
     * @return array
     */
    public function getKeyedByProperty($property)
    {
        $keyed = [];
        foreach ($this as $model) {
            $keyed[$model->$property] = $model;
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
        foreach ($this as $model) {
            if (empty($keyed[$model->$property])) {
                $keyed[$model->$property] = [];
            }
            $keyed[$model->$property][] = $model;
        }
        return $keyed;
    }
}
