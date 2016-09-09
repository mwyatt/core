<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    public function insert(array $data)
    {
        $data['timeRegistered'] = time();
        $this->adapter->prepare($this->getInsertGenericSql(['email', 'password', 'timeRegistered', 'nameFirst', 'nameLast']));
        $this->adapter->bindParam(':email', $data['email'], $this->adapter->getParamStr());
        $this->adapter->bindParam(':password', $data['password'], $this->adapter->getParamStr());
        $this->adapter->bindParam(':timeRegistered', $data['timeRegistered'], $this->adapter->getParamInt());
        $this->adapter->bindParam(':nameFirst', $data['nameFirst'], $this->adapter->getParamStr());
        $this->adapter->bindParam(':nameLast', $data['nameLast'], $this->adapter->getParamStr());
        $this->adapter->execute();
        $data['id'] = $this->adapter->getLastInsertId();
        return $this->getModelLazy($data);
    }


    public function update(\Mwyatt\Core\Model\User $model)
    {
        $this->adapter->prepare($this->getUpdateGenericSql(['email', 'password', 'nameFirst', 'nameLast']));
        $this->adapter->bindParam(':email', $model->get('email'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':password', $model->get('password'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':nameFirst', $model->get('nameFirst'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':nameLast', $model->get('nameLast'), $this->adapter->getParamStr());
        $this->adapter->bindParam(":id", $model->get('id'), $this->adapter->getParamInt());
        $this->adapter->execute();
        return $this->adapter->getRowCount();
    }
}
