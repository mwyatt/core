<?php

namespace Mwyatt\Core\Model;

class User extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\ModelInterface
{
    use \Mwyatt\Core\Model\IdTrait;

    protected $email;
    protected $nameFirst;
    protected $nameLast;
    protected $password;
    protected $timeCreated;
    public $logs = [];


    public function __construct()
    {
        $this->timeCreated = time();
    }


    public function setEmail($value)
    {
        $value = trim($value);
        $this->email = $value;
    }


    public function setNameFirst($value)
    {
        $value = trim($value);
        if (strlen($value) < 3) {
            $this->errors[] = "First name '$value' is too short.";
        } elseif (strlen($value) > 75) {
            $this->errors[] = "First name '$value' is too long.";
        }
        $this->nameFirst = $value;
    }


    public function setNameLast($value)
    {
        $value = trim($value);
        if (strlen($value) < 3) {
            $this->errors[] = "Last name '$value' is too short.";
        } elseif (strlen($value) > 75) {
            $this->errors[] = "Last name '$value' is too long.";
        }
        $this->nameLast = $value;
    }


    public function setPassword($value)
    {
        $this->password = $value;
    }


    public function createPassword($value)
    {
        if (strlen($value) < 6) {
            $this->errors[] = "Password is too short.";
        } elseif (strlen($value) > 20) {
            $this->errors[] = "Password is too long.";
        }
        $this->password = md5($value);
    }


    public function checkPassword($value)
    {
        return $this->password === crypt($value);
    }


    private function validateEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Email '{$this->email}' is invalid.";
        }
        if (strlen($this->email) > 50) {
            $this->errors[] = "Email is longer than 50 characters.";
        }
        if (strlen($this->email) < 1) {
            $this->errors[] = "Email is required.";
        }
    }


    private function validatePassword()
    {
        if (strlen($this->password) > 255) {
            $this->errors[] = "Password is too long.";
        }
        if (strlen($this->password) < 1) {
            $this->errors[] = "Password is too short.";
        }
    }


    private function validateNameFirst()
    {
        if (strlen($this->nameFirst) > 75) {
            $this->errors[] = "First name is too long.";
        }
        if (strlen($this->nameFirst) < 1) {
            $this->errors[] = "First name is too short.";
        }
    }


    private function validateNameLast()
    {
        if (strlen($this->nameLast) > 75) {
            $this->errors[] = "Last name is too long.";
        }
        if (strlen($this->nameLast) < 1) {
            $this->errors[] = "Last name is too short.";
        }
    }


    private function validateTimeCreated()
    {
        if ($this->timeCreated < 1) {
            $this->errors[] = "Time created is invalid.";
        }
    }


    public function getNameFull()
    {
        return $this->get('nameFirst') . ' ' . $this->get('nameLast');
    }


    public function validate()
    {
        $this->validateId();
        $this->validatePassword();
        $this->validateEmail();
        $this->validateNameFirst();
        $this->validateNameLast();
        $this->validateTimeCreated();
        return $this->errors;
    }


    public function jsonSerialize()
    {
        $this->password = null;
        return $this;
    }
}
