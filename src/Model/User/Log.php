<?php

namespace Mwyatt\Core\Model\User;

class Log extends \Mwyatt\Core\AbstractModel implements \Mwyatt\Core\ModelInterface
{
    use \Mwyatt\Core\Model\IdTrait;

    protected $userId;
    protected $logId;


    public function setUserId($value)
    {
        return $this->userId = $value;
    }


    public function setLogId($value)
    {
        return $this->logId = $value;
    }
}
