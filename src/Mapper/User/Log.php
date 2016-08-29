<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    public function persist(\Mwyatt\Core\Model\LogInterface $userLog)
    {
        return $this->lazyPersist($userLog, ['userId', 'logId']);
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
}
