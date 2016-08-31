<?php

namespace Mwyatt\Core\Mapper;

class User extends \Mwyatt\Core\AbstractMapper implements \Mwyatt\Core\MapperInterface
{


    /**
     * is this the best way to handle insert|update?
     * will be more convinient using just one method instead of two
     * @param  \Mwyatt\Core\Model\User $user
     * @return object|string    the object or error string.
     */
    public function persist(\Mwyatt\Core\Model\User $user)
    {
        return $this->lazyPersist($user, [
            'email',
            'password',
            'timeRegistered',
            'nameFirst',
            'nameLast'
        ]);
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
