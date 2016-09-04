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


    public function insertLog(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $log = $mapperLog->getModel();

        try {
            $mapperUserLog->beginTransaction();
            $log->setContent($userLog->getContent());
            $mapperLog->persist($log);
            $userLog->setLogId($log->get('id'));
            $mapperUserLog->insert($userLog);
            $mapperUserLog->commit();
        } catch (\Exception $e) {
            $mapperUserLog->rollback();
            throw new \Exception("Unable to insert user log.");
        }
    }


    public function deleteLog(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');

        try {
            $mapperUserLog->beginTransaction();
            $mapperLog->delete([$userLog]);
            $mapperUserLog->delete([$userLog]);
            $mapperUserLog->commit();
        } catch (\Exception $e) {
            $mapperUserLog->rollback();
            throw new \Exception("Unable to delete user log.");
        }
    }


    public function register(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        $mapperUser->persist($user);
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
