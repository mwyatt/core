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


    public function getName()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setEmailAddress($value)
    {
        \Assert\Assertion::assertEmail($value);
        // assert max length
        $this->nameFirst = $value;
    }


    public function setNameFirst($value)
    {
        \Assert\Assertion::assertString($value);
        // assert min length
        // assert max length
        // assert is a string
        $this->nameFirst = $value;
    }


    public function setNameFirst()
    {
        # code...
    }
}
