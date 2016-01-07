<?php
namespace Mwyatt\Core\Model;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Pagination
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
