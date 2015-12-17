<?php

namespace Mwyatt\Core\Mapper;

/**
 * phpunit tests
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Address extends \Mwyatt\Core\Mapper
{


    public $tableName = 'address';


    protected function getEntity(array $row) {
        return new \Mwyatt\Core\Model\Address(
            $row['id'],
            $row['postCode'],
            $row['personId']
        );
    }

    
    public function insert(\Mwyatt\Core\Model\Address $add) {
        $person->id = $this->database->insert(
            $this->getTableName(),
            [
                "title" => $person->title,
                "content" => $person->content
            ]);
        return $person->id;
    }


    public function delete($id) {
        if ($id instanceof \Mwyatt\Core\Model\Address) {
            $id = $id->id;
        }
        return $this->database->delete($this->getTableName(), "id = $id");
    }
}
