<?php

namespace Mwyatt\Core\Repository;

class User extends \Mwyatt\Core\AbstractRepository
{


    public function register($email, $password)
    {
        $userMapper = $this->getMapper();
        $user = $this->getModel();
        $user->setEmail($email);
        $user->createPassword($password);
        $userMapper->persist($user);
        return $user;
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
