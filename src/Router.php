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
            if (get_class($route) == 'Mwyatt\\Core\\Entity\\Route') {
                $this->mux->{$route->type}($route->path, [$route->controller, $route->method], $route->options);
                $this->routes[] = $route;
            }
        }
    }


    /**
     * obtain response
     * @return object Response
     */
    private function readResponse($path)
    {
        $route = $this->mux->dispatch($path);
        $response = new \Mwyatt\Core\Response('');
echo '<pre>';
print_r($route);
echo '</pre>';
exit;

        // found
        if ($route) {

            // do controller->method
            try {
                $response = \Pux\Executor::execute($route);

            // 500
            } catch (\Framework\Route\Exception $exception) {
                $controller = new \Mwyatt\Core\Controller;
                $response = $controller->e500($exception);
            }

        // not found
        } else {
            $response->setStatusCode(404);
        }

        // not found (could have been defined in controller)
        if ($response->getStatusCode() == 404 && !$response->getContent()) {
            $controller = new \Mwyatt\Core\Controller;
            $response = $controller->e404($response);
        }

        // ResponseInterface
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
