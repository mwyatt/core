<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class User
{


    protected $id;


    protected $nameFirst;


    protected $nameLast;


    protected $activity;


    protected $emailAddress;


    public function getNameFull()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setEmailAddress($value)
    {
        // test email here
        // throw exceptions
    }
}
