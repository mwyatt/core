<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class DomainObjectFactory extends \Mwyatt\Core\Factory
{


	public function __construct()
	{
		$this->setDefaultNamespace('\\Mwyatt\\Core\\DomainObject');
	}
}
