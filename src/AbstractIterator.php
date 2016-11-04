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
        $items = [];
        if ($this->offsetExists($index)) {
            $items = $this->offsetGet($index);
            if (!is_array($items)) {
                $itemsType = gettype($items);
                throw new \Exception("View offset '$index' is '$itemsType' and must be array to append.");
            }
        }
        $items[] = $value;
        $this->offsetSet($index, $items);
    }


    public function getFirst()
    {
        $this->rewind();
        return $this->current();
    }


    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
