<?php

namespace Mwyatt\Core\Service;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Person extends \Mwyatt\Core\Service
{


	public function getAll()
	{
		$mapperPerson = $this->dataMapperFactory->get('Person');
		$mapperPerson->read();
		$this->domainObjectFactory->get('Person')
	}
}
