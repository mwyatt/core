<?php

namespace Mwyatt\Core\Service;

class User extends \Mwyatt\Core\AbstractService
{


    public function findLogs(\Mwyatt\Core\ModelIterator $users)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $userIds = $users->extractProperty('id');
        $userLogs = $mapperUserLog->findByUserIds($userIds);

        foreach ($userLogs as $userLog) {
            $userGroup = $users->getByPropertyValue('id', $userLog->get('userId'));
            $user = current($userGroup);
            if ($user) {
                $user->logs->append($userLog);
            }
        }
    }


    public function insertLog(array $userLogData)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $log = $this->getModel('Log');
        $userLog = $this->getModel('User\Log');

        try {
            $userLog->setUserId($userLogData['userId']);
            $userLog->setContent($userLogData['content']);
            $log->setContent($userLogData['content']);
            $mapperLog->persist($log);
            $userLog->setLogId($log->get('id'));
            $mapperUserLog->persist($userLog);
        } catch (Exception $e) {

        }

        return $userLog;
    }


    /**
     * wip
     * @param  int $userLogId
     * @return int rowcount
     */
    public function deleteLogById($userLogId)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $userLogs = $mapperUserLog->findByIds($userLogId);
        if (!$userLogs->count()) {
            return;
        }
        $logs = $mapperLog->findByIds($userLogs->extractProperty('logId'));
        foreach ($variable as $key => $value) {
            # code...
        }
        return;
    }


    /**
     * is this the place where the object will be validated for correctness?
     * the mapper should not care about whether the object is correct
     * @param  array  $userData
     * @return object
     */
    public function register(array $userData)
    {
        $mapperUser = $this->getMapper('User');
        $user = $this->getModel('User');
        try {
            $user->setEmail($userData['email']);
            $user->setNameFirst($userData['nameFirst']);
            $user->setNameLast($userData['nameLast']);
            $user->setPassword($userData['password']);
            $user = $mapperUser->persist($user);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $user;
    }


    public function update(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        return $mapperUser->persist($user);
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');

        $userLogs = $mapperUserLog->findByUserIds([$user->get('id')]);
        foreach ($userLogs as $userLog) {
            $mapperUserLog->delete($userLog);
        }
        $logIds = $userLogs->extractProperty('logId');
        $logs = $mapperLog->findByIds($logIds);

        return $mapperUser->delete($user);
    }
}
