<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Controller implements \Mwyatt\Core\ControllerInterface
{


    protected $serviceFactory;


    protected $view;


    public function __construct(
        \Mwyatt\Core\ServiceFactory $serviceFactory,
        \Mwyatt\Core\ViewInterface $view
    )
    {
        $this->serviceFactory = $serviceFactory;
        $this->view = $view;
    }


    public function respond($content = '', $statusCode = 200)
    {
        return new \Mwyatt\Core\Response($content, $statusCode);
    }


    public function getService($name)
    {
        $this->serviceFactory->get($name);
    }


    /**
     * redirects the user to another url and terminates
     * utilising the generator from symfony
     * could this be a static function?
     * @param  string $key      routeKey
     * @param  array $config if required
     * @return null
     */
    public function redirect($key, $config = [])
    {

        // generate string to redirect to from url
        // redirect
        header('location:' . $this->url->generate($key, $config));

        // always prevent continuation
        exit;
    }
}
