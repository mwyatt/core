<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


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

        if (!$this->adapter->prepare($sql)) {
            return;
        }

        $this->adapter->bindParam(':email', $model->get('email'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':password', $model->get('password'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':timeRegistered', $model->get('timeRegistered'), $this->adapter->getParamInt());
        $this->adapter->bindParam(':nameFirst', $model->get('nameFirst'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':nameLast', $model->get('nameLast'), $this->adapter->getParamStr());

        if ($modelId) {
            $this->adapter->bindParam(":id", $modelId, $this->adapter->getParamInt());
        }

        if (!$this->adapter->execute()) {
            return;
        }

        if ($modelId) {
            $model->setId($this->adapter->getLastInsertId());
            $rowCount = 1;
        } else {
            $rowCount = $this->adapter->getRowCount();
        }

        return $rowCount;
    }


    // must remove user, userLog and log entries
    // first transaction attempt!
    public function delete(\Mwyatt\Core\Model\User $user)
    {
        

        if ($this->adapter->beginTransaction()) {
            $sql = ['delete', 'from', $this->table, 'where id = ?'];
            $rowCount = 0;

            $this->adapter->prepare(implode(' ', $sql));

            foreach ($models as $model) {
                $this->adapter->bindParam(1, $model->get('id'), $this->adapter->getParamInt());
                $this->adapter->execute();
                $rowCount += $this->adapter->getRowCount();
            }

            return $rowCount;
    
        }


        $this->adapter->rollBack();

        $this->adapter->commit();

    }
}
