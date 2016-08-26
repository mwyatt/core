<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{


    public function insert(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $this->database->prepare("
            insert into

can do both the log and the relationship here
perhaps use the log mapper here?

                `log`.`content`,
                `log`.`timeCreated`
            from `userLog`
            left join `log` on `userLog`.`logId` = `log`.`id`
            where `userLog`.`userId` = ?
        ");
        $collections = [];
        foreach ($userIds as $userId) {
            $this->database->execute([$userId]);
            $collections[] = $this->getIterator($this->database->fetchAll($this->fetchType, $this->model));
        }
        return $collections;
    }


    public function readByUserIds($userIds)
    {
        $this->database->prepare("
            select
                `log`.`content`,
                `log`.`timeCreated`
            from `userLog`
            left join `log` on `userLog`.`logId` = `log`.`id`
            where `userLog`.`userId` = ?
        ");
        $collections = [];
        foreach ($userIds as $userId) {
            $this->database->execute([$userId]);
            $collections[] = $this->getIterator($this->database->fetchAll($this->fetchType, $this->model));
        }
        return $collections;
    }
}
