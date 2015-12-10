<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
abstract class Service extends \Mwyatt\Core\Data
{


	protected $dataMapperFactory;


	protected $domainObjectFactory;


	public function __construct(
		\Mwyatt\Core\DataMapperFactory $dataMapperFactory,
		\Mwyatt\Core\DomainObjectFactory $domainObjectFactory,
	)
	{
	    $this->dataMapperFactory = $dataMapperFactory;
	    $this->domainObjectFactory = $domainObjectFactory;
	}
}
