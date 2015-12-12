<?php

namespace Mwyatt\Core\Mapper;

/**
 * phpunit tests
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Person extends \Mwyatt\Core\Mapper
{
    public $tableName = 'person';
    public $entity = '\\Mwyatt\\Core\\Entity\\Person';
}
