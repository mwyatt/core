<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Router implements \Mwyatt\Core\RouterInterface
{


    /**
     * \Pux\Mux
     * @var object
     */
    public $mux;


    /**
     * paths to files containing routes
     * @var array
     */
    public $routeSources = [];


    /**
     * init mux
     */
    public function __construct(\Pux\Mux $mux)
    {
        $this->mux = $mux;
    }


    public function appendRouteSource($filePath)
    {
        if (!is_file($filePath)) {
            throw new \Exception('filePath does not exist');
        }
        $this->routeSources[] = $filePath;
        return $this;
    }


    /**
     * obtains response object from matched controller
     * falls back to 404, or hits 500
     * echos the response content
     */
    public function matchRoute($path)
    {
        $response = $this->readResponse($path);
        $this->setHeaders($response);
        return $response;
    }


    public function getUrlRoutes()
    {
        $response = [];
        foreach ($mux->getRoutes() as $route) {
            $response[$route[3]['id']] = empty($route[3]['pattern']) ? $route[1] : $route[3]['pattern'];
        }
        return $response;
    }


    /**
     * obtain response
     * perhaps store a base controller so that you are able to control
     * the 404 and 500 responses?
     * @return object Response
     */
    private function dispatchRoute($route)
    {
        $route = $this->mux->dispatch($path);
        $response = new \Mwyatt\Core\Response('');

        // found route
        if ($route) {
            // do controller->method
            try {
                $response = \Pux\Executor::execute($route);

            // 500 - unexpected error
            } catch (\Exception $exception) {
                $response = $controller->e500($exception);
            }

        // 404 - not found
        } else {
            $response->setStatusCode(404);
        }

        // 404 content, could have been defined in a found route
        if ($response->getStatusCode() == 404) {
            $response = $controller->e404($response);
        }

        // the response
        return $response;
    }


    /**
     * just response code for now
     */
    private function setHeaders(\Mwyatt\Core\ResponseInterface $response)
    {
        http_response_code($response->getStatusCode());
    }
}
