<?php

namespace Mwyatt\Core\Model\User;

class Log extends \Mwyatt\Core\Model\Log implements \Mwyatt\Core\Model\LogInterface
{

    
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
