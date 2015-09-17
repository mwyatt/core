<?php

interface DataInterface
{
	
	
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
}