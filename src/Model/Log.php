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
            $this->setTimeCreated(time());
        }
        return $this->timeCreated;
    }


    protected function setTimeCreated($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->minLength(1);
        $assertionChain->integer();
        $this->timeCreated = $value;
    }
}
