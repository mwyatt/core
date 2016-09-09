<?php

namespace Mwyatt\Core\Repository;

class User extends \Mwyatt\Core\AbstractRepository
{


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


    public function insertLog(array $data)
    {
        $userMapper = $this->getMapper('User');
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');

        $log = $logMapper->insert($data);
        $data['logId'] = $log->get('id');
        $userLogMapper->insert($data);
        return $log;
    }


    public function findLogs(\Mwyatt\Core\AbstractIterator $users)
    {
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');
        $userLogs = $userLogMapper->findByUserIds($users->getIds());
        $logs = $logMapper->findByIds($userLogs->extractProperty('logId'));
        foreach ($users as $user) {
            $userLogSlice = $userLogs->getByPropertyValues('userId', [$user->get('id')]);
            $user->logs = $logs->getByPropertyValues('id', $userLogSlice->extractProperty('logId'));
        }
    }


    public function deleteLogs(\Mwyatt\Core\AbstractIterator $userLogs)
    {
        $userLogMapper = $this->getMapper('User\Log');
        $logMapper = $this->getMapper('Log');
        $logs = $logMapper->findByIds($userLogs->extractProperty('logId'));
        $logMapper->deleteById($logs);
        $userLogMapper->deleteById($userLogs);
    }


    public function delete(\Mwyatt\Core\AbstractIterator $users)
    {
        $userMapper = $this->getMapper('User');
        $userLogMapper = $this->getMapper('User\Log');
        $userLogs = $userLogMapper->findByUserIds($users->getIds());
        $this->deleteLogs($userLogs);
        $userMapper->deleteById($users);
    }
}
