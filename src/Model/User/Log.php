<?php

namespace Mwyatt\Core\Model\User;

class Log extends \Mwyatt\Core\AbstractModel
{

    
    protected $id;
    protected $userId;
    protected $logId;


    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : '';
        $this->setUserId($data['userId']);
        $this->setLogId($data['logId']);
        return $this;
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
