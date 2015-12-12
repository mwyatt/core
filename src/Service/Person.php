<?php

namespace Mwyatt\Core\Service;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Person extends \Mwyatt\Core\Service
{


	public function getAll()
	{
		$mapperPerson = $this->mapperFactory->get('Person');
		$people = $mapperPerson->read();
		$modelPerson = $this->modelFactory->get('Person');
		
		$modelPerson->setNameFirst($);
		return $people;
	}
}
