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
        \Pimple\Container $serviceFactory,
        \Mwyatt\Core\View $view
    ) {
    
        $this->serviceFactory = $serviceFactory;
        $this->view = $view;
    }


    /**
     * get a response object
     * @param  string  $content
     * @param  integer $statusCode
     * @return object
     */
    public function response($content = '', $statusCode = 200)
    {
        return new \Mwyatt\Core\Response($content, $statusCode);
    }


    /**
     * get service from the pimple container
     * @param  string $name
     * @return object
     */
    public function get($name)
    {
        return $this->serviceFactory[$name];
    }


    /**
     * redirects the user to another url and terminates
     * utilising the generator from symfony
     * could this be a static function?
     * @param  string $key      routeKey
     * @param  array $config if required
     * @return null
     */
    public function redirect($key, $config = [], $statusCode = 302)
    {
        $url = $this->get('Url');
        $urlNew = $url->generate($key, $config);

        // generate string to redirect to from url
        header('location:' . $urlNew, true, $statusCode);

        // for testing?
        return $urlNew;
    }


    /**
     * renders a template and returns the string
     * @param  string $templatePath
     * @return string
     */
    public function render($templatePath)
    {
        return $this->view->getTemplate($templatePath);
    }


    /**
     * 404 not found exception, will be caught in the routing area
     * @param  string $message what was not found
     * @return object
     */
    public function exceptionNotFound($message = '')
    {
        return new Mwyatt\Core\Controller\Exception\NotFound($message);
    }
}
