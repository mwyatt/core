<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function createModel(array $data)
    {
        $model = new $this->model(isset($data['id']) ? $data['id'] : 0);
        $model->setContent($data['content']);
        $model->setTimeCreated($data['timeCreated']);
        return $model;
    }


    public function insert(array $data)
    {
        $data['timeCreated'] = time();
        try {
            $this->createModel($data);
            $this->adapter->prepare($this->getInsertGenericSql(['content', 'timeCreated']));
            $this->adapter->bindParam(':content', $data['content'], $this->adapter->getParamStr());
            $this->adapter->bindParam(':timeCreated', $data['timeCreated'], $this->adapter->getParamInt());
            $this->adapter->execute();
            $data['id'] = $this->adapter->getLastInsertId();
            return $this->createModel($data);
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    public function update(\Mwyatt\Core\Model\LogInterface $model)
    {
        try {
            $this->adapter->prepare($this->getUpdateGenericSql(['content']));
            $this->adapter->bindParam(':content', $model->get('content'), $this->adapter->getParamStr());
            $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            return $this->adapter->getRowCount();
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }
}
