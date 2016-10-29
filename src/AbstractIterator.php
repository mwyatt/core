<?php

namespace Mwyatt\Core;

abstract class AbstractIterator extends \ArrayIterator implements \Mwyatt\Core\IteratorInterface, \JsonSerializable
{


    /**
     * adds a value to an offset array, if there is an array
     * @param  mixed $index
     * @param  mixed $value
     * @return bool
     */
    public function offsetAppend($index, $value)
    {
        $itemOffset = $this->offsetGet($index);
        $items = $itemOffset ? $itemOffset : [];
        if (!is_array($items)) {
            throw new \Exception("View offset $index is not an array.");
        }
        $items[] = $value;
        $this->offsetSet($index, $items);
    }


    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
