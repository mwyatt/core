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
            if ($user = $this->collection->getByPropertyValue('id', $userLog->get('userId'))) {
                $user->logs->add($userLog);
            }
        }
    }


    public function insertLog(array $userLogData)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $userLog = $this->getModel('User\Log');

        try {
            $userLog->setUserId($userLogData['userId']);
            $userLog->setContent($userLogData['content']);
            $logId = $mapperLog->insert($userLog);
            $userLog->setLogId($logId);
            $userLogId = $mapperUserLog->insert($userLog);
        } catch (Exception $e) {
            return;
        }

        return true;
    }


    public function insert(array $userData)
    {
        $mapperUser = $this->getMapper('User');
        $user = $this->getModel('User');
        try {
            $user->setEmail($userData['email']);
            $user->setNameFirst($userData['nameFirst']);
            $user->setNameLast($userData['nameLast']);
            $user->setPassword($userData['password']);
            $user = $mapperUser->insert($user);
        } catch (Exception $e) {
            return;
        }
        return $user;
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
