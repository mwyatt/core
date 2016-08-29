<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    public function persist(\Mwyatt\Core\Model\LogInterface $log)
    {
        return $this->lazyPersist($log, ['content', 'timeCreated']);
    }
}
