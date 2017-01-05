<?php

namespace Mwyatt\Core;

abstract class AbstractController implements \Mwyatt\Core\ControllerInterface
{
    protected $pimpleContainer;
    protected $view;


    public function __construct(
        \Pimple\Container $pimpleContainer,
        \Mwyatt\Core\ViewInterface $view
    ) {
    
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


    protected function getMapper($name)
    {
        $mapperFactory = $this->getService('MapperFactory');
        return $mapperFactory->get($name);
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
     * deprecated and must use redirectAbs
     */
    public function redirect($key, $config = [], $statusCode = 302)
    {
        $url = $this->getService('Url');
        $urlNew = $url->generate($key, $config);
        $this->redirectAbs($urlNew, $statusCode);
    }


    /**
     * must be renamed to redirect in next major version
     * @param  string  $url        absolute
     * @param  integer $statusCode
     */
    public function redirectAbs($url, $statusCode = 302)
    {
        header('location:' . $url, true, $statusCode);
        exit;
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
