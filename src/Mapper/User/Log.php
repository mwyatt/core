<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{


    public function readByUserIds($userIds)
    {
        $this->database->prepare("
            select
                `log`.`content`,
                `log`.`timeCreated`
            from `userLog`
            left join `log` on `userLog`.logId = `log`.id
            where `userLog`.userId = ?
        ");
        foreach ($userIds as $userId) {
            $this->database->execute([$userId]);
        }
        
        return $this->getIterator($this->database->fetchAll($this->fetchType, $this->model));


        return $this->database->getRowCount();
    }
}
