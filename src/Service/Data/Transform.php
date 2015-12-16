<?php

namespace Mwyatt\Core\Service\Data;

/**
 * always returns domain objects?
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Transform extends \Mwyatt\Core\Service
{


	/**
	 * builds an array of {property} from the data property
	 * for ones which are objects
	 * @param  string $property
	 * @return array
	 */
	public function getProperty($collection, $property)
	{
		
	    foreach ($this->getData() as $possibleObject) {
	        if (is_object($possibleObject) && property_exists($possibleObject, $property)) {
	            $collection[] = $possibleObject->$property;
	        }
	    }
	    return $collection;
	}
}
