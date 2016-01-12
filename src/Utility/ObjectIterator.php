<?php

namespace Mwyatt\Core\Utility;

/**
 * extends the arrayiterator which allows for complex transforms
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class ObjectIterator implements \ArrayIterator
{


    /**
     * unset all iterator and return a copy
     * @return array
     */
    private function unsetGetCopy()
    {
        $thisCopy = $this->getArrayCopy();

        // done 2 times because first time it leaves one?
        foreach ($this as $key => $value) {
            $this->offsetUnset($key);
        }
        foreach ($this as $key => $value) {
            $this->offsetUnset($key);
        }
        return $thisCopy;
    }
    

    /**
     * key the iterator by the specified property
     * only 1 level deep
     * @param  string $property
     * @return null
     */
    public function keyByProperty($property)
    {
        $thisCopy = $this->unsetGetCopy();
        foreach ($thisCopy as $model) {
            $this[$model->$property] = $model;
        }
    }


    /**
     * key the iterator by the specified property
     * but go 2 levels
     * @param  string $property
     * @return null
     */
    public function keyByPropertyMulti($property)
    {
        $thisCopy = $this->unsetGetCopy();
        foreach ($thisCopy as $value) {
            if (empty($this[$value->$property])) {
                $this[$value->$property] = [];
            }
            $this[$value->$property][] = $value;
        }
    }


    /**
     * remove entries where property == value
     * @param  string $property
     * @param  mixed $value
     * @return null
     */
    public function filterOutByProperty($property, $value)
    {
        foreach ($this as $key => $model) {
            if ($model->$property == $value) {
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
            $collection[] = $value->$property;
        }
        return $collection;
    }


    /**
     * get the model which matches the property value
     * @param  string $property
     * @param  mixed $value
     * @return object
     */
    public function getByPropertyValue($property, $value)
    {
        foreach ($this as $model) {
            if ($model->get($property) == $value) {
                return $model;
            }
        }
    }


    /**
     * get the model which matches the property value
     * @param  string $property
     * @param  mixed $value
     * @return object
     */
    public function getByPropertyValueMulti($property, $value)
    {
        $models = [];
        foreach ($this as $model) {
            if ($model->get($property) == $value) {
                $models[] = $model;
            }
        }
        return $models;
    }
}
