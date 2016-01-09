<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
abstract class ServiceAbstract
{


    protected $mapperFactory;


    protected $modelFactory;


    public function __construct(
        \Mwyatt\Core\MapperFactory $mapperFactory,
        \Mwyatt\Core\ModelFactory $modelFactory
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->modelFactory = $modelFactory;
    }
}
