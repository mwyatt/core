<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface RouterInterface
{
    public function __construct(\Pux\Mux $mux);
    public function getMux();
    public function appendMuxRoutes(array $routes);
    public function getMuxRouteCurrent($path);
    public function setMuxRouteCurrent($route);
    public function getMuxRouteCurrentController();
    public function getMuxRouteCurrentControllerMethod();
    public function executeRoute(array $route);
    public function getUrlRoutes();
    public function setHeaders(\Mwyatt\Core\ResponseInterface $response);
}
