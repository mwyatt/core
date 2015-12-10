<?php
namespace Mwyatt\Core\Entity;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Person extends \Mwyatt\Core\Entity
{


    /**
     * @var string
     */
    public $name;


    /**
     * @var int
     */
    public $telephoneLandline;


    /**
     * will be constructed in a domain object
     * @var array
     */
    public $addresses;
}
