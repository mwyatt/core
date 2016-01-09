<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Person
{


    public $id;


    public $nameFirst;


    public $nameLast;


    public $addresses;


    public $actions;


    public function getName()
    {
        return $this->nameFirst . ' ' . $this->nameLast;
    }


    public function setName($name)
    {
        $names = explode(' ', $name);
        if (count($names) < 2) {
            throw new \Exception;
        }
        $this->nameFirst = reset($names);
        $this->nameLast = end($names);
    }
}
