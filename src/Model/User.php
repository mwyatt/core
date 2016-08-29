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
            $this->timeRegistered = time();
        }
        return $this->timeRegistered;
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
}
