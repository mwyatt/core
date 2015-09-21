<?php

namespace Mwyatt\Core\Model;

/**
 * phpunit tests
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Test extends \Mwyatt\Core\Model
{


    public $tableName = 'test';
    public $entity = '\\Mwyatt\\Core\\Entity\\Test';
    public $fields = [
        'id',
        'bar'
    ];
}
