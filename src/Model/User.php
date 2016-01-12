<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User extends \Mwyatt\Core\ModelAbstract
{


    protected $id;


    protected $nameFirst;


    protected $nameLast;


    protected $emailAddress;

    
    public $activity;


    public function getNameFull()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setEmailAddress($value)
    {
        \Assert\Assertion::assertEmail($value);
        $this->nameFirst = $value;
    }


    public function setNameFirst($value)
    {
        \Assert\Assertion::assertString($value);
        $this->nameFirst = $value;
    }
}
