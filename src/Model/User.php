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


    /**
     * this is an issue, how to solve?
     * should not be able to set this
     */
    public function setId($value)
    {
        $value = $value + 0;
        if (!$value) {
            throw new \Exception("User id '$value' is invalid.");
        }
        $this->id = $value;
    }


    /**
     * needed because when inserting it needs to be in the model
     * it should not be possible to set this
     * @return int 
     */
    public function getTimeRegistered()
    {
        if (!$this->timeRegistered) {
            $this->timeRegistered = time();
        }
        return $this->timeRegistered;
    }


    public function setEmail($value)
    {
        $value = trim($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("User email '$value' is invalid.");
        } elseif (strlen($value) > 50) {
            throw new \Exception("User email '$value' is invalid.");
        }
        $this->email = $value;
    }


    public function setNameFirst($value)
    {
        $value = trim($value);
        if (strlen($value) < 3) {
            throw new \Exception("User first name '$value' is too short.");
        } elseif (strlen($value) > 75) {
            throw new \Exception("User first name '$value' is too long.");
        }
        $this->nameFirst = $value;
    }


    public function setNameLast($value)
    {
        $value = trim($value);
        if (strlen($value) < 3) {
            throw new \Exception("User last name '$value' is too short.");
        } elseif (strlen($value) > 75) {
            throw new \Exception("User last name '$value' is too long.");
        }
        $this->nameLast = $value;
    }


    public function setPassword($value)
    {
        if (strlen($value) > 255) {
            throw new \Exception("User password is too long.");
        }
        $this->password = $value;
    }


    public function createPassword($value)
    {
        if (strlen($value) < 6) {
            throw new \Exception("User password is too short.");
        } elseif (strlen($value) > 20) {
            throw new \Exception("User password is too long.");
        }
        $value = md5($value);
        $this->setPassword($value);
    }


    public function validatePassword($value)
    {
        return $this->password === crypt($value);
    }


    public function getNameFull()
    {
        return $this->get('nameFirst') . ' ' . $this->get('nameLast');
    }


    public function jsonSerialize()
    {
        $this->password = null;
        return $this;
    }
}
