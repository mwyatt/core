<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\Model\LogInterface
{

    
    protected $id;
    protected $content;
    protected $timeCreated;


    public function setContent($value)
    {
        $this->content = $value;
        return true;
    }
}
