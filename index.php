<?php

include 'vendor/autoload.php';
$basePath = (string) __DIR__ . '/';

$database = new \Mwyatt\Core\Database\Pdo(include $basePath . 'config.php');

$mapperFactory = new \Mwyatt\Core\MapperFactory($database);
$mapperFactory->setDefaultNamespace('\\Mwyatt\\Core\\Mapper\\');

$modelFactory = new \Mwyatt\Core\ModelFactory;
$modelFactory->setDefaultNamespace('\\Mwyatt\\Core\\Model\\');

$serviceFactory = new \Mwyatt\Core\ServiceFactory(
    $mapperFactory,
    $modelFactory
);
$serviceFactory->setDefaultNamespace('\\Mwyatt\\Core\\Service\\');

$router = new \Mwyatt\Core\Router(new \Pux\Mux);
$router->appendMuxRoutes([$basePath . 'routes.php']);

$url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/', 'core/');
$route = $router->getRoute($url->getPath());

$class = '\\Mwyatt\\Core\\View\\' . $route[3]['view'];
$view = new $class($serviceFactory);
$view->prependTemplatePath((string) (__DIR__ . '/template/'));

$class = $route[2][0];
$controller = new $class($serviceFactory, $view);

$request = new \Mwyatt\Core\Request;

$command = $route[2][1];
$response = $controller->{$command}($request);

echo $response->getContent();
