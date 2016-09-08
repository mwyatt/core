<?php

namespace Mwyatt\Core\Repository;

class User extends \Mwyatt\Core\AbstractRepository
{


    public function findLogs(\Mwyatt\Core\AbstractIterator $users)
    {
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');
        $userLogs = $userLogMapper->findByUserIds($users->getIds());
        $logs = $logMapper->findByIds($userLogs->extractProperty('logId'));
        foreach ($users as $user) {
            $userLogSlice = $userLogs->getByPropertyValue('userId', $user->get('id'));
            $user->logs = $logs->getByPropertyValues('id', $userLogSlice->extractProperty('logId'));
        }
    }


    public function insertLog(array $data)
    {
        $userMapper = $this->getMapper('User');
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');

        try {
            $userMapper->beginTransaction();
            $log = $logMapper->insert($data);
            $data['logId'] = $log->get('id');
            $userLogMapper->insert($data);
            $userMapper->commit();
            return $log;
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
            exit;
            
            $userMapper->rollback();
            throw new \Exception("Problem while inserting.");
        }
    }


    public function deleteLog(\Mwyatt\Core\Model\User\Log $userLog)
    {
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');

        try {
            $userLogMapper->beginTransaction();
            $logs = $logMapper->findByIds($userLog->get('logId'));
            $logMapper->delete($logs);
            $userLogMapper->delete([$userLog]);
            $userLogMapper->commit();
        } catch (\Exception $e) {
            $userLogMapper->rollback();
            throw new \Exception("Unable to delete user log.");
        }
    }


    public function register(array $data)
    {
        $userMapper = $this->getMapper('User');
        $data['password'] = $this->createPassword($data['password']);
        return $userMapper->insert($data);
    }


    protected function createPassword($value)
    {
        $assertionChain = \Assert\that($value); // better way to get this?
        $assertionChain->minLength(6);
        $assertionChain->maxLength(20);
        return md5($value);
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $userMapper = $this->getMapper('User');
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');

        try {
            $userMapper->beginTransaction();
            $userLogs = $userLogMapper->findByUserIds([$user->get('id')]);
            $logs = $logMapper->findByIds($userLogs->extractProperty('logId'));
            $logMapper->delete($logs);
            $userLogMapper->delete($userLogs);
            $userMapper->delete([$user]);
            $userMapper->commit();
        } catch (\Exception $e) {
            $userMapper->rollback();
            throw new \Exception("Unable to delete user.");
        }
    }
}
