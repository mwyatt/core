<?php

namespace Mwyatt\Core\Controller;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Person extends \Mwyatt\Core\Controller
{


    public function all()
    {
        $servicePerson = $this->getService('Person');
        $people = $servicePerson->getAll();
        echo '<pre>';
        print_r($people);
        echo '</pre>';
        exit;
        
        return $this->respond();
    }


    public function single($request)
    {

        return new \Mwyatt\Core\Response('testSimple', 200);
    }
}
