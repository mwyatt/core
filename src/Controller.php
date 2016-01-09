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
        \Mwyatt\Core\View $view
    )
    {
        $this->serviceFactory = $serviceFactory;
        $this->view = $view;
    }


    public function response($content = '', $statusCode = 200)
    {
        return new \Mwyatt\Core\Response($content, $statusCode);
    }


    public function get($name)
    {
        return $this->serviceFactory->get($name);
    }


    /**
     * redirects the user to another url and terminates
     * utilising the generator from symfony
     * could this be a static function?
     * @param  string $key      routeKey
     * @param  array $config if required
     * @return null
     */
    public function redirect($key, $statusCode = 200, $config = [])
    {

        // generate string to redirect to from url
        header('location:' . $this->url->generate($key, $config), true, $statusCode);

        // always prevent continuation
        exit;
    }
}
