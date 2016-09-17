<?php

namespace Mwyatt\Core\Iterator;

class Model extends \Mwyatt\Core\AbstractIterator
{


    /**
     * lazy extract
     * @return array
     */
    public function getIds()
    {
        return $this->extractProperty('id');
    }

    
    /**
     * lazy for getbypropertyvalue
     * @param  int $id
     * @return object
     */
    public function getById($id)
    {
        if ($models = $this->getByPropertyValues('id', [$id])) {
            return $models->current();
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
            $collection[] = $model->get($property);
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
                if ($model->get($property) == $value) {
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
            $keyed[$model->get($property)] = $model;
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
            if (empty($keyed[$model->get($property)])) {
                $keyed[$model->get($property)] = [];
            }
            $keyed[$model->get($property)][] = $model;
        }
        return $keyed;
    }
}
