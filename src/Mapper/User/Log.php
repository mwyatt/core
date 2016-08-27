<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\MapperAbstract implements \Mwyatt\Core\MapperInterface
{


    public function insert(\Mwyatt\Core\Model\LogInterface $userLog)
    {
        $this->database->prepare("insert into `userLog` (`userId`, `logId`) values (?, ?)");
        $this->database->execute([
            $userLog->get('userId'),
            $userLog->get('logId')
        ]);
        return $this->database->getLastInsertId();
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
            $collections[] = $this->database->fetch($this->fetchType, $this->model);
        }
        echo '<pre>';
        print_r($collections);
        echo '</pre>';
        exit;
        
        return $this->getIterator($collections);
    }
}
