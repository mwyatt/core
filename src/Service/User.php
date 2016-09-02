<?php

namespace Mwyatt\Core\Service;

class User extends \Mwyatt\Core\AbstractService
{


    public function badMethod()
    {
        $mapperUser = $this->getMapper('User');
        $mapperUser->badMethod();

    }


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

        $log = $mapperLog->create($userLogData);
        $mapperLog->persist($log);
        $userLogData['logId'] = $log->get('id');
        $userLog = $mapperUserLog->create($userLogData);
        $mapperUserLog->insert($userLog);

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
     * expect exception from this
     * @param  array  $userData
     * @return object|exception
     */
    public function register(array $userData)
    {
        $mapperUser = $this->getMapper('User');
        $user = $this->getModel('User');
        $user = $mapperUser->create($userData);
        $mapperUser->persist($user);
        return $user;
    }


    public function update(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        return $mapperUser->persist($user);
    }


    /**
     * @param  \Mwyatt\Core\Model\User
     * @return bool
     */
    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $userLogs = $mapperUserLog->findByUserIds([$user->get('id')]);
        $logs = $mapperLog->findByIds($userLogs->extractProperty('logId'));

        try {
            $mapperUser->beginTransaction();
            $mapperUserLog->delete($userLogs);
            $mapperLog->delete($logs);
            $mapperUser->delete($user);
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
            exit;
            
            $mapperUser->rollback();
            return;
        }

        return $mapperUser->commit();
    }
}
