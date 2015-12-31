<?php

namespace Mwyatt\Core;

/**
 * grouping of model patterns
 * uses full functionality from Data
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class ServiceFactory extends \Mwyatt\Core\Factory
{


    protected $mapperFactory;


    protected $modelFactory;


    // public function __construct(
    // 	\Mwyatt\Core\MapperFactory $mapperFactory,
    // 	\Mwyatt\Core\ModelFactory $modelFactory
    // )
    // {
    //     $this->mapperFactory = $mapperFactory;
    //     $this->modelFactory = $modelFactory;
    // }


    public function get($name)
    {
        $namespace = $this->defaultNamespace . $name;
        return new $namespace(
            $this->mapperFactory,
            $this->modelFactory
        );
    }
}
