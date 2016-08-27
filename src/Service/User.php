<?php

namespace Mwyatt\Core\Service;

class User extends \Mwyatt\Core\ServiceAbstract
{


    public function findLogs()
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $userIds = $this->collection->extractProperty('id');
        $userLogs = $mapperUserLog->readByUserIds($userIds);
        echo '<pre>';
        print_r($userLogs);
        echo '</pre>';
        exit;
        
        foreach ($userLogs as $userLog) {
            $user = $this->collection->getByPropertyValue('id', $userLog->get('userId'));
            $user->logs->add($userLog);
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
