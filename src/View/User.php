<?php

namespace Mwyatt\Core\View;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class User extends \Mwyatt\Core\View
{


    public function all()
    {
    	return $this->getTemplate('person/all');
    }
}
