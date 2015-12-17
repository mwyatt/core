<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Address
{


	public $id;


    public $postCode;


	public $personId;


    public function __construct(
        $id,
        $postCode,
        $personId
    )
    {
        $this->id = $id;
        $this->postCode = $postCode;
        $this->personId = $personId;
    }
}
