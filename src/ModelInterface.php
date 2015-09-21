<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface ModelInterface
{


    /**
     * @return string
     */
    public function getEntity();
    

    /**
     * @return array
     */
    public function getFields();


    /**
     * @return string
     */
    public function getTableName();


    /**
     * data = ids created [1, 5, 2]
     * @param  array $entities
     * @return object
     */
    public function create(array $entities);


    /**
     * reads everything
     * data = array of entities
     * @return object
     */
    public function read();


    /**
     * read single column with multiple values
     * @param  array  $values     
     * @param  string $columnName 
     * @return object             
     */
    public function readColumn(array $values, $columnName = 'id');


    /**
     * uses the passed properties to build named prepared statement
     * data = array of id => status [1, 0]
     * struggling to use the id as it may not always be there..
     * @param  array  $molds
     * @param  string $by    defines the column to update by
     * @return int
     */
    public function update(array $entities, $column = 'id');


    /**
     * uses where property to build delete statement
     * improved to allow entities to be passed
     * data = array of id => status [1 => 1, 2 => 0]
     * @param  array  $properties
     * @return int
     */
    public function delete(array $entities, $column = 'id');
}
