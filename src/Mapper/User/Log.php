<?php

namespace Mwyatt\Core\Mapper\User;

class Log extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    public function createModel(array $data)
    {
        $model = new $this->model(isset($data['id']) ? $data['id'] : 0);
        $model->setUserId($data['userId']);
        $model->setLogId($data['logId']);
        return $model;
    }


    public function insert(array $data)
    {
        try {
            $this->createModel($data);
            $this->adapter->prepare($this->getInsertGenericSql(['userId', 'logId']));
            $this->adapter->bindParam(':userId', $data['userId'], $this->adapter->getParamInt());
            $this->adapter->bindParam(':logId', $data['logId'], $this->adapter->getParamInt());
            $this->adapter->execute();
            $data['id'] = $this->adapter->getLastInsertId();
            return $this->createModel($data);
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    public function findByUserIds(array $userIds)
    {
        try {
            $models = [];
            $this->adapter->prepare("select * from `userLog` where `userId` = ?");
            foreach ($userIds as $userId) {
                $this->adapter->bindParam(1, $userId, $this->adapter->getParamInt());
                $this->adapter->execute();
                while ($data = $this->adapter->fetch($this->adapter->getFetchTypeAssoc())) {
                    $models[] = $this->createModel($data);
                }
            }
            return $this->getIterator($models);
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }
}
