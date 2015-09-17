<?php

namespace OriginalAppName;

/**
 * manipulates arrays of objects (usually entities)
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Data implements \OriginalAppName\DataInterface
{


    /**
     * universal storage property, used for many things
     * @var array
     */
    public $data = [];
    
    
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
     * get all data
     * @param  string $key
     * @return any
     */
    public function getData($key = '')
    {
        $data = $this->data;

        // specific key
        if (isset($data[$key])) {
            return $this->data[$key];
        }

        // key wanted, so pass nothing
        if ($key) {
            return;
        }

        // all data
        return $this->data;
    }


    /**
     * retrieves the first row of data, if there is any
     * @return object, array, bool
     */
    public function getDataFirst()
    {
        $data = $this->getData();
        if (! $data) {
            return;
        }
        return reset($data);
    }


    /**
     * builds an array of {property} from the data property
     * @param  string $property
     * @return array
     */
    public function getDataProperty($property)
    {
        if (! $data = $this->getData()) {
            return [];
        }
        $collection = [];
        foreach ($data as $entity) {
            $collection[] = $entity->$property;
        }
        return $collection;
    }


    /**
     * append 1 to the data array
     * @todo is this slow/ok?
     * @param  any $dataRow
     * @return object          instance
     */
    public function appendData($dataRow)
    {
        if (! $dataRow) {
            return $this;
        }
        $data = $this->getData();
        $data[] = $dataRow;
        $this->setData($data);
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
     * @param  string $property
     * @return array
     */
    public function keyDataByProperty($property)
    {
        if (! $data = $this->getData()) {
            return;
        }
        $newOrder = array();
        $dataSample = current($data);
        if (! property_exists($dataSample, $property)) {
            return;
        }
        foreach ($data as $mold) {
            $newOrder[$mold->$property] = $mold;
        }
        $this->setData($newOrder);
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
        if (! $data = $this->getData()) {
            return $this;
        }
        $dataSample = current($data);
        if (! property_exists($dataSample, $column)) {
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
     * @todo try and make more readable
     * @param  string $property database table column name
     * @param  string $order    asc|desc
     * @return object
     */
    public function orderByProperty($property, $order = 'asc')
    {
        if (!$data = $this->getData()) {
            return $this;
        }
        $dataSingle = current($data);
        if (! property_exists($dataSingle, $property)) {
            return;
        }
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
        $this->setData($data);
        return $this;
    }
}
