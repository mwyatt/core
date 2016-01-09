<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class MapperFactory extends \Mwyatt\Core\Factory
{


	protected $database;


	protected $modelFactory;


	public function __construct(
		\Mwyatt\Core\DatabaseInterface $database,
		\Mwyatt\Core\ModelFactory $modelFactory,
	)
	{
	    $this->database = $database;
	    $this->modelFactory = $modelFactory;
	}


	public function get($name)
	{
		$namespace = $this->defaultNamespace . $name;
		return new $namespace($this->database);
	}
}
