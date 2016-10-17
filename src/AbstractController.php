<?php

namespace Mwyatt\Core;

abstract class AbstractController implements \Mwyatt\Core\ControllerInterface
{
    protected $pimpleContainer;
    protected $view;


    public function __construct(
        \Pimple\Container $pimpleContainer,
        \Mwyatt\Core\View $view
    )
    {
        $this->pimpleContainer = $pimpleContainer;
        $this->view = $view;
    }


    /**
     * get service from the pimple container
     * @param  string $name
     * @return object
     */
    public function getService($name)
    {
        return $this->pimpleContainer[$name];
    }


    public function getRepository($name)
    {
        $repositoryFactory = $this->getService('RepositoryFactory');
        return $repositoryFactory->get($name);
    }


    public function getView($name)
    {
        $viewFactory = $this->getService('ViewFactory');
        return $viewFactory->get($name);
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
     * redirects the user to another url and terminates
     * utilising the generator from symfony
     * could this be a static function?
     * should this be moved to Router? as it has been needed outside of controllers
     * @param  string $key      routeKey
     * @param  array $config if required
     * @return null
     */
    public function redirect($key, $config = [], $statusCode = 302)
    {
        $url = $this->getService('Url');
        $urlNew = $url->generate($key, $config);

        // generate string to redirect to from url
        header('location:' . $urlNew, true, $statusCode);

        // must exit because otherwise the script will continue
        exit;
    }


    /**
     * just refresh the page
     */
    public function redirectRefresh()
    {
        throw new \Exception('Unable to redirectRefresh.');
        
        $url = $this->getService('Url');
        // $urlNew = $url->generate($key, $config);

        // generate string to redirect to from url
        // header('location:' . $urlNew, true, $statusCode);

        // must exit because otherwise the script will continue
        // exit;
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
}
