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
        return parent::getFirst();
    }


    /**
     * lazy extract
     * @return array
     */
    public function getIds()
    {
        return $this->pluckUnique('id')->getArrayCopy();
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
        return $this->pluck($property)->getArrayCopy();
    }


    /**
     * get a collection of values from property unique
     * @param  string $property
     * @return array
     */
    public function extractPropertyUnique($property)
    {
        return $this->pluckUnique($property)->getArrayCopy();
    }


    public function getKeyedByProperty($property)
    {
        return parent::getKeyedByProperty($property)->getArrayCopy();
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
