<?php

namespace Mwyatt\Core\Service;

class User extends \Mwyatt\Core\ServiceAbstract
{


    public function findLogs()
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $userIds = $this->collection->extractProperty('id');
        $userLogs = $mapperUserLog->readByUserIds($userIds);
        foreach ($userLogs as $userLog) {
            $user = $this->collection->getByPropertyValue('id', $userLog->get('userId'));
            $user->logs->add($userLog);
        }
    }


    public function insertLog(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        try {
            $newId = $mapperUserLog->insert($userLog);
        } catch (Exception $e) {
            return -1;
        }
        return $newId;
    }


    public function insert(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        try {
            $newId = $mapperUser->insert($user);
        } catch (Exception $e) {
            return -1;
        }
        return $newId;
    }


    public function findById($id)
    {
        $mapperUser = $this->getMapper('User');
        $modelUsers = $mapperUser->findColumn([$id], 'id');
        return $modelUsers->current();
    }


    public function deleteById($userId)
    {
        $mapperUser = $this->getMapper('User');
        return $mapperUser->deleteById($userId);
    }
}
