<?php

namespace Mwyatt\Core\Controller;

class Error extends \Mwyatt\Core\AbstractController
{


    public function e404()
    {
        $this->view->offsetSet('title', '404 Not Found');
        $this->view->offsetSet('description', 'Unable to find.');
        return $this->response($this->view->getTemplate('message'), 404);
    }


    public function e500($systemError = '')
    {
        $this->view->offsetSet('title', 'Server Error');
        $this->view->offsetSet('description', 'Something went wrong while executing this script, please try again.');
        $this->view->offsetSet('systemError', $systemError);
        return $this->response($this->view->getTemplate('message'), 500);
    }


    public function maintenance()
    {
        $configService = $this->getService('Config');
        $maintenanceVal = $configService->getSetting('core.maintenance');
        $dateBack = $maintenanceVal > 1 ? ' and will return ' . date('G:i d-m-Y', $maintenanceVal) : '';
        $this->view->offsetSet('title', 'We will be back soon');
        $this->view->offsetSet('description', "The website is undergoing essential maintenance$dateBack.");
        return $this->response($this->view->getTemplate('message'), 503);
    }
}
