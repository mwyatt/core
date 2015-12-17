<?php

namespace Mwyatt\Core;

/**
 * manipulates arrays of objects (usually entities)
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class ObjectIterator implements \ArrayIterator
{


    protected function unsetGetCopy()
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
    

    public function keyByProperty($property)
    {
        $thisCopy = $this->unsetGetCopy();
        foreach ($thisCopy as $entity) {
            $this[$entity->$property] = $entity;
        }
    }


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


    public function filterOutByProperty($property, $value)
    {
        foreach ($this as $key => $entity) {
            if ($entity->$property == $value) {
                $this->offsetUnset($key);
            }
        }
    }


    public function extractProperty($property)
    {
        $collection = [];
        foreach ($this as $value) {
            $collection[] = $value->$property;
        }
        return $collection;
    }
}
