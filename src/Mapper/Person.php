<?php

namespace Mwyatt\Core\Mapper;

/**
 * phpunit tests
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Person extends \Mwyatt\Core\MapperAbstract
{
    

    public $tableName = 'person';


    protected $addressMapper;


    protected function getEntity(array $row)
    {
        $comments = $this->addressMapper->findAll(
            array("post_id" => $row["id"])
        );
            return new Post($row["title"], $row["content"], $comments);
    }


    public function __construct(
        \Mwyatt\Core\DatabaseInterface $database,
        \Mwyatt\Core\Mapper\Address $addressMapper
    ) {
    
        $this->addressMapper = $addressMapper;
        parent::__construct($database);
    }


    public function insert(\Mwyatt\Core\Model\Person $person)
    {
        $person->id = $this->database->insert(
            $this->getTableName(),
            [
                "title" => $person->title,
                "content" => $person->content
            ]
        );
        return $person->id;
    }


    public function delete($id)
    {
        if ($id instanceof \Mwyatt\Core\Model\Person) {
            $id = $id->id;
        }
        $this->database->delete($this->getTableName(), "id = $id");
        return $this->addressMapper->delete("post_id = $id");
    }
}
