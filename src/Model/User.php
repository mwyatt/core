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
    public $logs = [];


    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : '';
        $this->setEmail($data['email']);
        $this->setNameFirst($data['nameFirst']);
        $this->setNameLast($data['nameLast']);
        $this->setTimeRegistered($data['timeRegistered']);
        $this->setPassword($data['password']);
        return $this;
    }


    public function getNameFull()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setTimeRegistered($value)
    {
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


    public function validatePassword($value)
    {
        return $this->password === crypt($value);
    }


    public function jsonSerialize()
    {
        $this->setPassword('');
        return $this;
    }
}
