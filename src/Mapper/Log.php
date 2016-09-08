<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function insert(array $data)
    {
        $data['timeCreated'] = time();
        try {
            $this->adapter->beginTransaction();
            $this->adapter->prepare($this->getInsertGenericSql(['content', 'timeCreated']));
            $this->adapter->bindParam(':content', $data['content'], $this->adapter->getParamStr());
            $this->adapter->bindParam(':timeCreated', $data['timeCreated'], $this->adapter->getParamInt());
            $this->adapter->execute();
            if ($data['id'] = $this->adapter->getLastInsertId()) {
                $this->adapter->commit();
                return $this->getModelLazy($data);
            } else {
                throw new \PDOException('Unexpected response from storage adapter.');
            }
        } catch (\PDOException $e) {
            $this->adapter->rollBack();
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    public function update(\Mwyatt\Core\Model\Log $model)
    {
        try {
            $this->adapter->beginTransaction();
            $this->adapter->prepare($this->getUpdateGenericSql(['content']));
            $this->adapter->bindParam(':content', $model->get('content'), $this->adapter->getParamStr());
            $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            $this->adapter->commit();
            return $this->adapter->getRowCount();
        } catch (\PDOException $e) {
            $this->adapter->rollBack();
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }
}
