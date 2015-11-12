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


    public $rootControllerName = '\\Mwyatt\\Core\\Controller';


    /**
     * \Framework\Route\Definition
     * @var array
     */
    public $routes = [];


    /**
     * init mux
     */
    public function __construct()
    {
        $this->mux = new \Pux\Mux;
    }


    /**
     * obtains response object from matched controller
     * falls back to 404, or hits 500
     * echos the response content
     */
    public function getResponse($path)
    {
        $response = $this->readResponse($path);
        $this->setHeaders($response);
        return $response;
    }


    /**
     * add to the definition registry
     * @param  string $path to the new routes array
     * @return null
     */
    public function appendRoutes(array $routes)
    {

        // append to mux and collection array
        // must be instances of the correct entity
        foreach ($routes as $route) {
            $this->mux->{$route->type}($route->path, [$route->controller, $route->method], empty($route->options) ? [] : $route->options);
            $this->routes[] = $route;
        }
    }


    /**
     * obtain response
     * perhaps store a base controller so that you are able to control
     * the 404 and 500 responses?
     * @return object Response
     */
    private function readResponse($path)
    {
        $route = $this->mux->dispatch($path);
        $response = new \Mwyatt\Core\Response('');
        $controller = new $this->rootControllerName;

        // store final routes
        $registry = \Mwyatt\Core\Registry::getInstance();
        $registry->set('routes', $this->routes);

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
