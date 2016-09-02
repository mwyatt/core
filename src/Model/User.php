<?php

namespace Mwyatt\Core\Model;

class User extends \Mwyatt\Core\AbstractModel
{


    protected $id;
    protected $email;
    protected $nameFirst;
    protected $nameLast;
    protected $password;
    protected $timeRegistered;

    public $logs;


    public function __construct()
    {
        $this->logs = new \Mwyatt\Core\ModelIterator;
    }


    public function setId($id)
    {
        return $this->id = $id;
    }


    public function getNameFull()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function getTimeRegistered()
    {
        if (!$this->timeRegistered) {
            $this->setTimeRegistered(time());
        }
        return $this->timeRegistered;
    }


    protected function setTimeRegistered($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->minLength(1);
        $assertionChain->integer();
        $this->timeRegistered = $value;
    }


    public function setEmail($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->maxLength(50);
        $assertionChain->email($value);
        $this->email = $value;
    }


    protected function assertName($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->minLength(3);
        $assertionChain->maxLength(75);
        $assertionChain->string($value);
        return $value;
    }


    public function setNameFirst($value)
    {
        $this->nameFirst = $this->assertName($value);
    }


    public function setNameLast($value)
    {
        $this->nameLast = $this->assertName($value);
    }


    public function setPassword($value)
    {
        $assertionChain = $this->getAssertionChain($value);
        $assertionChain->maxLength(255);
        $this->password = $value;
    }
}
