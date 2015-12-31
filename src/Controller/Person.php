<?php

namespace Mwyatt\Core\Controller;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Person extends \Mwyatt\Core\Controller
{


    public function all($request)
    {
        $servicePerson = $this->getService('Person');
        $this->view->data->offsetSet('people', $servicePerson->getAll());
        return $this->response($this->view->all());
    }


    public function create($request)
    {
        $servicePerson = $this->getService('Person');
        $servicePerson->create($request->get('name'), $request->get('telephoneLandline'));
    }
}
