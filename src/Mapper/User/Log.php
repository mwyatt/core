<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function insert(array $data)
    {
        $this->adapter->prepare($this->getInsertGenericSql(['userId', 'logId']));
        $this->adapter->bindParam(':userId', $data['userId'], $this->adapter->getParamInt());
        $this->adapter->bindParam(':logId', $data['logId'], $this->adapter->getParamInt());
        $this->adapter->execute();
        $data['id'] = $this->adapter->getLastInsertId();
        return $this->getModelLazy($data);
    }


    public function findByUserIds(array $userIds)
    {
        $models = [];
        $this->adapter->prepare("select * from `userLog` where `userId` = ?");
        foreach ($userIds as $userId) {
            $this->adapter->bindParam(1, $userId, $this->adapter->getParamInt());
            $this->adapter->execute();
            while ($data = $this->adapter->fetch()) {
                $models[] = $this->getModelLazy($data);
            }
        }
        return $this->getIterator($models);
    }
}
