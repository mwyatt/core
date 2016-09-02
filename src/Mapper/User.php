<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper
{


    public function badMethod()
    {
        
    }


    /**
     * @param  \Mwyatt\Core\Model\User $user
     * @return bool
     */
    public function persist(\Mwyatt\Core\Model\User $model)
    {
        $modelId = $model->get('id');
        $cols = [
            'email',
            'password',
            'timeRegistered',
            'nameFirst',
            'nameLast'
        ];
        if ($modelId) {
            $sql = $this->getUpdateGenericSql($cols);
        } else {
            $sql = $this->getInsertGenericSql($cols);
        }

        $this->adapter->prepare($sql);

        $this->adapter->bindParam(':email', $model->get('email'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':password', $model->get('password'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':timeRegistered', $model->get('timeRegistered'), $this->adapter->getParamInt());
        $this->adapter->bindParam(':nameFirst', $model->get('nameFirst'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':nameLast', $model->get('nameLast'), $this->adapter->getParamStr());

        if ($modelId) {
            $this->adapter->bindParam(":id", $modelId, $this->adapter->getParamInt());
        }

        $this->adapter->execute();

        if ($modelId) {
            $rowCount = $this->adapter->getRowCount();
        } else {
            $model->setId($this->adapter->getLastInsertId());
            $rowCount = 1;
        }

        return $rowCount;
    }


    public function delete(\Mwyatt\Core\Model\User $user)
    {
        $sql = ['delete', 'from', $this->table, 'where id = ?'];
        $this->adapter->prepare(implode(' ', $sql));
        $this->adapter->bindParam(1, $user->get('id'), $this->adapter->getParamInt());
        $this->adapter->execute();
        return $this->adapter->getRowCount();
    }
}
