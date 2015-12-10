<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class DataMapperFactory extends \Mwyatt\Core\Factory
{


	protected $database;


	public function __construct(\Mwyatt\Core\Database $database)
	{
	    $this->database = $database;
		$this->setDefaultNamespace('\\Mwyatt\\Core\\Model');
	}
}