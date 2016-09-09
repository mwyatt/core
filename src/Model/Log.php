<?php

namespace Mwyatt\Core\Model;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\Model\LogInterface
{

    
    protected $id;
    protected $content;
    protected $timeCreated;


    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : '';
        $this->setContent($data['content']);
        $this->setTimeCreated($data['timeCreated']);
        return $this;
    }


    public function setContent($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->minLength(3);
        $assertionChain->maxLength(255);
        $assertionChain->string($value);
        $this->content = $value;
    }


    public function setTimeCreated($value)
    {
        $this->timeCreated = $value;
    }
}
