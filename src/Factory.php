<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Factory
{


	protected $defaultNamespace;


	public function setDefaultNamespace($namespace)
	{
		$this->defaultNamespace = $namespace;
	}


	public function get($name)
	{
		$namespace = $this->defaultNamespace . $name;
		return new $namespace;
	}
}
