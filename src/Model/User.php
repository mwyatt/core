<?php

namespace Mwyatt\Core\Model;

class User extends \Mwyatt\Core\ModelAbstract
{


    protected $id;
    protected $nameFirst;
    protected $password;
    protected $timeRegistered;
    protected $nameLast;
    protected $email;

    
    protected $activity;


    public function getNameFull()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setEmail($value)
    {
        \Assert\Assertion::maxLength($value, 50);
        \Assert\Assertion::email($value);
        $this->email = $value;
    }


    protected function assertName($value)
    {
        \Assert\Assertion::minLength($value, 3);
        \Assert\Assertion::maxLength($value, 75);
        \Assert\Assertion::string($value);
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
        \Assert\Assertion::maxLength($value, 255);
        $this->password = $value;
    }


    public function setTimeRegistered($value)
    {
        \Assert\Assertion::integer($value);
        $this->timeRegistered = $value;
    }
}
