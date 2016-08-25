<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel
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
