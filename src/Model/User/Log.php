<?php

namespace Mwyatt\Core\Model\User;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\ModelInterface
{
    protected $id;
    protected $userId;
    protected $logId;


    public function setId($value)
    {
        $value = $value + 0;
        if (!$value) {
            throw new \Exception("Log id '$value' is invalid.");
        }
        $this->id = $value;
    }


    public function setUserId($value)
    {
        return $this->userId = $value;
    }


    public function setLogId($value)
    {
        return $this->logId = $value;
    }
}
