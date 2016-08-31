<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


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


    public function findByUserIds($userIds)
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
            $this->adapter->execute([$userId]);
            if ($userLog = $this->adapter->fetch($this->fetchType, $this->model)) {
                $userLog->setUserId($userId);
                $userLogs[] = $userLog;
            }
        }
        return $this->getIterator($userLogs);
    }


    public function deleteById()
    {
        $this->adapter->transacationBegin();
    }
}
