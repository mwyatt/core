<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    public function insert(array $data)
    {
        $data['timeRegistered'] = time();
        try {
            $this->adapter->beginTransaction();
            $this->adapter->prepare($this->getInsertGenericSql(['email', 'password', 'timeRegistered', 'nameFirst', 'nameLast']));
            $this->adapter->bindParam(':email', $data['email'], $this->adapter->getParamStr());
            $this->adapter->bindParam(':password', $data['password'], $this->adapter->getParamStr());
            $this->adapter->bindParam(':timeRegistered', $data['timeRegistered'], $this->adapter->getParamInt());
            $this->adapter->bindParam(':nameFirst', $data['nameFirst'], $this->adapter->getParamStr());
            $this->adapter->bindParam(':nameLast', $data['nameLast'], $this->adapter->getParamStr());
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


    public function update(\Mwyatt\Core\Model\User $model)
    {
        try {
            $this->adapter->beginTransaction();
            $this->adapter->prepare($this->getUpdateGenericSql(['email', 'password', 'nameFirst', 'nameLast']));
            $this->adapter->bindParam(':email', $model->get('email'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':password', $model->get('password'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':nameFirst', $model->get('nameFirst'), $this->adapter->getParamStr());
            $this->adapter->bindParam(':nameLast', $model->get('nameLast'), $this->adapter->getParamStr());
            $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            $this->adapter->commit();
        } catch (\PDOException $e) {
            $this->adapter->rollBack();
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }


    public function deleteSingle(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['delete', 'from', $this->getTableName(), 'where id = ?'];
        try {
            $this->adapter->prepare(implode(' ', $sql));
            $this->adapter->bindParam(1, $user->get('id'), $this->adapter->getParamInt());
            $this->adapter->execute();
            if ($this->adapter->getRowCount() !== 1) {
                throw new \PDOException('Unexpected response from storage adapter.');
            }
        } catch (\PDOException $e) {
            throw new \Mwyatt\Core\DatabaseException("Problem while communicating with database.");
        }
    }
}
