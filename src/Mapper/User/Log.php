<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function create(array $data)
    {
        $this->testKeys($data, ['userId', 'logId']);
        $model = $this->getModel();
        $model->setUserId($data['userId']);
        $model->setLogId($data['logId']);
        return $model;
    }


    public function insert(\Mwyatt\Core\Model\User\Log $model)
    {
        if ($model->get('id')) {
            return;
        }
        $cols = ['userId', 'logId'];
        $sql = $this->getInsertGenericSql($cols);

        if (!$this->adapter->prepare($sql)) {
            return;
        }

        $this->adapter->bindParam(':userId', $model->get('userId'), $this->adapter->getParamInt());
        $this->adapter->bindParam(':logId', $model->get('logId'), $this->adapter->getParamInt());

        if (!$this->adapter->execute()) {
            return;
        }
        
        $model->setId($this->adapter->getLastInsertId());
        $rowCount = 1;

        return $rowCount;
    }


    public function findByUserIds(array $userIds)
    {
        $this->adapter->prepare("
            select
                `userLog`.`id` as `id`,
                `userLog`.`logId`,
                `log`.`content`,
                `log`.`timeCreated`
            from `userLog`
            left join `log` on `userLog`.`logId` = `log`.`id`
            where `userLog`.`userId` = ?
        ");
        $userLogs = [];
        foreach ($userIds as $userId) {
            $this->adapter->bindParam(1, $userId, $this->adapter->getParamInt());
            $this->adapter->execute();
            if ($userLog = $this->adapter->fetch($this->fetchType, $this->model)) {
                $userLog->setUserId($userId);
                $userLogs[] = $userLog;
            }
        }
        return $this->getIterator($userLogs);
    }
}
