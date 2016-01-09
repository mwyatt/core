<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
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
