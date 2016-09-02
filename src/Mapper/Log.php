<?php

namespace Mwyatt\Core\Mapper;

class Log extends \Mwyatt\Core\AbstractMapper
{


    public function persist(\Mwyatt\Core\Model\LogInterface $model)
    {
        $modelId = $model->get('id');
        $cols = ['content', 'timeCreated'];
        if ($modelId) {
            $sql = $this->getUpdateGenericSql($cols);
        } else {
            $sql = $this->getInsertGenericSql($cols);
        }

        if (!$this->adapter->prepare($sql)) {
            return;
        }

        $this->adapter->bindParam(':content', $model->get('content'), $this->adapter->getParamStr());
        $this->adapter->bindParam(':timeCreated', $model->get('timeCreated'), $this->adapter->getParamInt());

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
}
