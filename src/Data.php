<?php

namespace Mwyatt\Core;

/**
 * manipulates arrays of objects (usually entities)
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Data implements \Mwyatt\Core\DataInterface
{


    /**
     * universal storage property, used for many things
     * @var array
     */
    public $data = [];


    /**
     * get date key
     * @param  string $key
     * @return mixed
     */
    public function getDataKey($key)
    {
        return empty($this->data[$key]) ? null : $this->data[$key];
    }


    /**
     * get all data
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * retrieves the first row of data, if there is any
     * @return mixed
     */
    public function getDataFirst()
    {
        $data = $this->getData();
        if (!$data) {
            return;
        }
        return reset($data);
    }


    /**
     * builds an array of {property} from the data property
     * for ones which are objects
     * @param  string $property
     * @return array
     */
    public function getDataProperty($property)
    {
        $collection = [];
        foreach ($this->getData() as $possibleObject) {
            if (is_object($possibleObject) && property_exists($possibleObject, $property)) {
                $collection[] = $possibleObject->$property;
            }
        }
        return $collection;
    }
    
    
    /**
     * @param mixed $data Usually array
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * set a specific data key
     * primary reason view needs to add / merge single keys
     * @param string $key
     * @param any $value
     */
    public function setDataKey($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }


    /**
     * append 1 to the data array
     * @param  any $value
     * @return object          
     */
    public function appendData($value)
    {
        $data = $this->getData();
        if (is_array($data)) {
            $data[] = $value;
            $this->setData($data);
        }
        return $this;
    }


    /**
     * limit the data stored to a range specified
     * @param  array $range [0, 1]
     * @return object
     */
    public function limitData($range)
    {
        $data = $this->getData();
        $this->setData(array_slice($data, $range[0], $range[1]));
        return $this;
    }


    /**
     * arranges this->data by a specified property
     * what if there are two, make array?
     * @param  string $property
     * @return array
     */
    public function keyDataByProperty($property)
    {
        $collection = [];

        // validation
        if (!$data = $this->getData()) {
            return $this;
        }
        if (!count($data)) {
            return $this;
        }

        // storage
        foreach ($data as $entity) {
            if (is_object($entity) && property_exists($entity, $property)) {
                $collection[$entity->$property] = $entity;
            }
        }
        $this->setData($collection);
        return $this;
    }


    public function keyDataByPropertyMulti($property)
    {
        $collection = [];

        // validation
        if (!$data = $this->getData()) {
            return $this;
        }

        // storage
        foreach ($data as $entity) {
            if (empty($collection[$entity->$property])) {
                $collection[$entity->$property] = [];
            }
            $collection[$entity->$property][] = $entity;
        }
        $this->setData($collection);
        return $this;
    }


    /**
     * filters out any data that does not
     * column == value
     * @param  string $column database column
     * @param  any $value
     * @return object
     */
    public function filterData($column, $value)
    {
        if (!$data = $this->getData()) {
            return $this;
        }
        $dataSample = current($data);
        if (!property_exists($dataSample, $column)) {
            return;
        }
        $dataFiltered = array();
        foreach ($data as $entity) {
            if ($entity->$column == $value) {
                $dataFiltered[] = $entity;
            }
        }
        $this->setData($dataFiltered);
        return $this;
    }


    /**
     * combines existing data with new rows
     * @param  array $dataRows
     * @return object
     */
    public function mergeData($dataRows)
    {
        $data = $this->getData();
        $this->setData(array_merge($data, $dataRows));
        return $this;
    }


    /**
     * order by a property
     * @param  string $property
     * @param  string $order    asc else desc
     * @return object
     */
    public function orderByProperty($property, $order = 'asc')
    {
        if (!$data = $this->getData()) {
            return $this;
        }
        $dataSingle = current($data);
        if (!is_object($dataSingle) || !property_exists($dataSingle, $property)) {
            return $this;
        }

        // guess type
        $sampleValue = $dataSingle->$property;
        $type = 'integer';
        if (is_string($sampleValue)) {
            $type = 'string';
        } elseif (is_float($sampleValue)) {
            $type = 'float';
        } elseif (is_int($sampleValue)) {
            $type = 'integer';
        }

        // sort
        uasort($data, function ($a, $b) use ($property, $type, $order) {
            if ($type == 'float') {
                $a->$property += 0;
                $b->$property += 0;
            }
            if ($type == 'string') {
                if ($order == 'asc') {
                    return strcasecmp($a->$property, $b->$property);
                } else {
                    return strcasecmp($b->$property, $a->$property);
                }
            } elseif ($type == 'integer' || $type == 'float') {
                if ($order == 'asc') {
                    if ($a->$property == $b->$property) {
                        return 0;
                    }
                    return $a->$property < $b->$property ? -1 : 1;
                } else {
                    if ($a->$property == $b->$property) {
                        return 0;
                    }
                    return $a->$property > $b->$property ? -1 : 1;
                }
            }

        });

        // store new order
        $this->setData($data);
        return $this;
    }
}
