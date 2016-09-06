<?php

namespace Mwyatt\Core;

class ModelIterator extends \Mwyatt\Core\AbstractIterator
{


    /**
     * get the model which matches the property value
     * @param  string $property
     * @param  mixed $value
     * @return model
     */
    public function getByPropertyValue($property, $value)
    {
        $models = [];
        foreach ($this as $model) {
            if ($model->get($property) == $value) {
                $models[] = $model;
            }
        }
        return new $this($models);
    }


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


    public function getIds()
    {
        return $this->extractProperty('id');
    }


    /**
     * hmm?
     * @param  \Mwyatt\Core\ModelInterface $model
     */
    public function append(\Mwyatt\Core\ModelInterface $model)
    {
        $this[] = $model;
    }
}
