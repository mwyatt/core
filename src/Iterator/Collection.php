<?php

namespace Mwyatt\Core\Iterator;

class Collection extends \ArrayIterator implements \JsonSerializable
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


    public function append($item)
    {
        $this[] = $item;
        return new static($this);
    }


    public function getLast()
    {
        $this->rewind();
        $count = $this->count();
        for ($index = 1; $index < $count; $index++) {
            $this->next();
        }
        return $this->current();
    }


    /**
     * get a collection of values from property
     * @param  string $property
     * @return array
     */
    public function pluck($key, $unique = false)
    {
        $results = [];
        foreach ($this as $item) {
            $value = is_object($item) ? $item->$key : $item[$key];
            if ($unique) {
                $results[$value] = $value;
            } else {
                $results[] = $value;
            }
        }
        $collection = new static($results);
        return $collection->resetKeys();
    }


    /**
     * get a collection of values from property unique
     * @param  string $property
     * @return array
     */
    public function pluckUnique($key)
    {
        return $this->pluck($key, true);
    }


    /**
     * get the models which match the property values
     * loose matching not strict
     * @param  string $property
     * @param  mixed $value
     * @return object ModelIterator
     */
    public function getByPropertyValues($property, array $values, $strict = false)
    {
        $results = [];
        foreach ($this as $item) {
            foreach ($values as $value) {
                if ($strict) {
                    if ($item->$property === $value) {
                        $results[] = $item;
                    }
                } else {
                    if ($item->$property == $value) {
                        $results[] = $item;
                    }
                }
            }
        }
        return new static($results);
    }


    public function getByPropertyValuesStrict($property, array $values)
    {
        return $this->getByPropertyValues($property, $values, true);
    }


    /**
     * key the iterator by the specified property
     * @param  string $property
     * @return array
     */
    public function getKeyedByProperty($property)
    {
        $results = [];
        foreach ($this as $item) {
            $results[$item->$property] = $item;
        }
        return new static($results);
    }


    public function keys()
    {
        return new static(array_keys($this->getArrayCopy()));
    }


    /**
     * how to reset array keys here?
     * as when jsonencoded the keys must be reset
     * not sure you would always want to reset as may need
     * to maintain sometimes?
     */
    public function sort(callable $callback = null)
    {
        $items = $this->getArrayCopy();
        $callback ? uasort($items, $callback) : asort($items);
        return new static($items);
    }


    /**
     * how does this work?
     */
    public function map(callable $callback)
    {
        $items = $this->getArrayCopy();
        $keys = array_keys($items);
        $items = array_map($callback, $items, $keys);
        return new static(array_combine($keys, $items));
    }


    public function resetKeys()
    {
        return new static(array_values($this->getArrayCopy()));
    }


    public function next()
    {
        parent::next();
        return $this->current();
    }


    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->getArrayCopy(), $callback));
        }
        return new static(array_filter($this->getArrayCopy()));
    }


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
                throw new \Exception("Offset '$index' is '$itemsType' and must be array to append.");
            }
        }
        $items[] = $value;
        $this->offsetSet($index, $items);
    }


    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
