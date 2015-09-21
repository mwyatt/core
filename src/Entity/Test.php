<?php
namespace Mwyatt\Core\Entity;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Test
{


    /**
     * protected so that it cant be set (auto incrementing)
     * @var int
     */
    protected $id;


    /**
     * @var string
     */
    public $bar;


    /**
     * any entities with protected ids need to use this method
     * to obtain. this was introduced to prevent the setting of ids
     * on non writable fields. this can be applied to all other protected
     * fields in the future.
     * the fields will all need to be editable in the actual database
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
