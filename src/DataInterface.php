<?php
namespace Mwyatt\Core;

interface DataInterface
{
	

    /**
     * get all data
     * @param  string $key
     * @return mixed
     */
    public function getData($key = null);
	

    /**
     * retrieves the first row of data, if there is any
     * @return mixed
     */
    public function getDataFirst();


    /**
     * builds an array of {property} from the data property
     * for ones which are objects
     * @param  string $property
     * @return array
     */
    public function getDataProperty($property);
    

    /**
     * append 1 to the data array
     * @param  mixed $data
     * @return object          
     */
    public function appendData($data);


    /**
     * limit the data stored to a range specified
     * @param  array $range [0, 1]
     * @return object
     */
    public function limitData($range);


    /**
     * arranges this->data by a specified property
     * @param  string $property
     * @return array
     */
    public function keyDataByProperty($property);


	/**
	 * @param mixed $data Usually array
	 */
	public function setData($data);


	/**
	 * set a specific data key
	 * primary reason view needs to add / merge single keys
	 * @param string $key
	 * @param any $value
	 */
	public function setDataKey($key, $value);


    /**
     * combines existing data with new rows
     * @param  array $dataRows
     * @return object
     */
    public function mergeData($dataRows);


    /**
     * order by a property
     * @param  string $property
     * @param  string $order    asc else desc
     * @return object
     */
    public function orderByProperty($property, $order = 'asc');
}