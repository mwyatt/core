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
        $this->nameFirst = $value;
    }


    public function setNameLast($value)
    {
        $value = trim($value);
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


    public function validateNameFirst()
    {
        if (!strlen($this->nameFirst)) {
            return;
        }
        if (strlen($this->nameFirst) < 3) {
            $this->errors[] = "First name '$this->nameFirst' is too short.";
        } elseif (strlen($this->nameFirst) > 75) {
            $this->errors[] = "First name '$this->nameFirst' is too long.";
        }
    }


    public function validateNameLast()
    {
        if (!strlen($this->nameLast)) {
            return;
        }
        if (strlen($this->nameLast) < 3) {
            $this->errors[] = "Last name '$this->nameLast' is too short.";
        } elseif (strlen($this->nameLast) > 75) {
            $this->errors[] = "Last name '$this->nameLast' is too long.";
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


    protected function validateRules()
    {
        $this->validateId();
        $this->validatePassword();
        $this->validateEmail();
        $this->validateNameFirst();
        $this->validateNameLast();
        $this->validateTimeCreated();
    }


    public function jsonSerialize()
    {
        $this->password = null;
        return [
            'nameFirst' => $this->nameFirst,
            'nameLast' => $this->nameLast,
            'email' => $this->email,
        ];
    }
}
