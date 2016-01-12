<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
abstract class ModelAbstract
{


    protected $assert;


    public function __construct()
    {
        $this->assert = \Assert\Assertion;
    }
}
