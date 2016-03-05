<?php
namespace Mwyatt\Core\Model;

/*
    CREATE TABLE `user` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `email` varchar(50) NOT NULL DEFAULT '',
      `password` varchar(255) NOT NULL DEFAULT '',
      `timeRegistered` int(10) unsigned DEFAULT NULL,
      `nameFirst` varchar(75) NOT NULL DEFAULT '',
      `nameLast` varchar(75) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 */

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\ModelAbstract
{


    public $id;


    public $nameFirst;


    public $password;


    public $timeRegistered;


    public $nameLast;


    public $email;

    
    public $activity;


    // public function __construct(
    //     $nameFirst,
    //     $nameLast,
    //     $email,
    //     $password,
    //     $timeRegistered
    // )
    // {
    //     $this->setEmail($email)
    //     $this->setNameFirst($nameFirst)
    //     $this->setNameLast($nameLast)
    //     $this->setPassword($password)
    // }


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
