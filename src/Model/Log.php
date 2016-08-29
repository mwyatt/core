<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\Model\LogInterface
{

    
    protected $id;
    protected $content;
    protected $timeCreated;


    public function setId($id)
    {
        return $this->id = $id;
    }


    public function setContent($value)
    {
        $this->content = $value;
        return true;
    }


    public function getTimeCreated()
    {
        if (!$this->timeCreated) {
            $this->timeCreated = time();
        }
        return $this->timeCreated;
    }
}
