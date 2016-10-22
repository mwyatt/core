<?php

define('PATH_BASE', (string) (__DIR__ . '/'));
include PATH_BASE . 'vendor/autoload.php';
$kernel = new \Mwyatt\Core\Http\Kernel(PATH_BASE);

if (!empty($config['errorReporting'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
include PATH_BASE . 'vendor/autoload.php';
$pimple = include PATH_BASE . 'pimple.php';
\Monolog\ErrorHandler::register($pimple['log']);
$url = $pimple['Url'];
$request = $pimple['Request'];
$router = $pimple['Router'];
$view = $pimple['View'];
$routes = $pimple['Routes'];
$registry = \Mwyatt\Elttl\Registry::getInstance();
$registry->set('database', $pimple['Database']);
$route = $router->getMuxRouteCurrent('/' . $url->getPath());
if (!$route) {
    $route = [];
}
$controller = new \Mwyatt\Core\Controller($pimple, $view);
$request->setMuxUrlVars($route);
include $view->getPathBase('indexCommon.php');
if (strpos($url->getPath(), 'admin') === 0) {
    include $view->getPathBase('indexAdmin.php');
} else {
    include $view->getPathBase('indexFront.php');
}
$controllerError = new \Mwyatt\Elttl\Controller\Error($pimple, $view);
if ($route) {
    $controllerNs = '\\' . $router->getMuxRouteCurrentController();
    $controllerMethod = $router->getMuxRouteCurrentControllerMethod();
    $controller = new $controllerNs($pimple, $view);
    try {
        $response = $controller->$controllerMethod($request);
    } catch (\Exception $e) {
        $response = $controllerError->server($e->getMessage());
    }
} else {
    $response = $controllerError->route();
}
$router->setHeaders($response);
echo $response->getContent();
