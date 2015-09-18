<?php
namespace Mwyatt\Core\Entity;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Route
{


    /**
     * identifier to refer to
     * @var string
     */
    public $key;


    /**
     * http method
     * @var string
     */
    public $type;


    /**
     * relative path to match with :keys
     * @var string
     */
    public $path;


    /**
     * full namespace path to controller on match
     * @var string
     */
    public $controller;


    /**
     * method to use on match
     * @var string
     */
    public $method;


    /**
     * see corneltek/pux for options
     * @var array
     */
    public $options = [];
}
