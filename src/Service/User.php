<?php

namespace Mwyatt\Core\Service;

class User extends \Mwyatt\Core\AbstractService
{


    public function findLogs(\Mwyatt\Core\Iterator\Model $users)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');
        $userLogs = $mapperUserLog->findByUserIds($users->getIds());
        $logs = $mapperLog->findByIds($userLogs->getIds());
        foreach ($users as $user) {
            $userLogSlice = $userLogs->getByPropertyValue('userId', $user->get('id'));
            $user->logs = $logs->getByPropertyValues('id', $userLogSlice->extractProperty('logId'));
        }
    }


    public function insertLog(array $data)
    {
        $mapperUser = $this->getMapper('User');
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');

        try {
            $mapperUser->beginTransaction();
            $log = $mapperLog->insert($data);
            $data['logId'] = $log->get('id');
            $mapperUserLog->insert($data);
            $mapperUser->commit();
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
            exit;
            
            $mapperUser->rollback();
            throw new \Exception("Problem while inserting.");
        }
    }


    public function deleteLog(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $mapperUserLog = $this->getMapper('User\Log');
        $mapperLog = $this->getMapper('Log');

        try {
            $mapperUserLog->beginTransaction();
            $logs = $mapperLog->findByIds($userLog->get('logId'));
            $mapperLog->delete($logs);
            $mapperUserLog->delete([$userLog]);
            $mapperUserLog->commit();
        } catch (\Exception $e) {
            $mapperUserLog->rollback();
            throw new \Exception("Unable to delete user log.");
        }
    }


    public function register(array $data)
    {
        $mapperUser = $this->getMapper('User');

        // handle password creation here?
        // crypt($data['password'])

        return $mapperUser->insert($data);
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $mapperUser = $this->getMapper('User');
        $mapperUserLog = $this->getMapper('User\Log');

        try {
            $mapperUser->beginTransaction();
            $userLogs = $mapperUserLog->findByUserIds([$user->get('id')]);
            $logs = $mapperLog->findByIds($userLogs->extractProperty('logId'));
            $mapperLog->delete($logs);
            $mapperUserLog->delete($userLogs);
            $mapperUser->delete([$user]);
            $mapperUser->commit();
        } catch (\Exception $e) {
            $mapperUser->rollback();
            throw new \Exception("Unable to delete user.");
        }
    }
}
