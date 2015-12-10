<?php

include 'vendor/autoload.php';
$basePath = (string) __DIR__ . '/';

$database = new \Mwyatt\Core\Database\Pdo;
$database->setCredentials(include $basePath . 'config.php');

$serviceFactory = new \Mwyatt\Core\ServiceFactory(
    new \Mwyatt\Core\DataMapperFactory($database),
    new \Mwyatt\Core\DomainObjectFactory
);
$serviceFactory->setDefaultNamespace('\\Mwyatt\\Core\\Service');

$router = new \Mwyatt\Core\Router(new \Pux\Mux);
$router->appendMuxRoutes([$basePath . 'routes.php']);

$url = new \Mwyatt\Core\Url('192.168.1.24/', '/core/', 'core/');
$route = $router->getRoute($url->getPath());

$class = '\\Mwyatt\\Core\\View\\' . $route[3]['view'];
$view = new $class($serviceFactory);
$view->setDefaultTemplateLocation(__DIR__ . '/templates');

/*
 * Initialization of Controller
 */
$class = '\\Application\\Controller\\' . $request->getResourceName();
$controller = new $class($serviceFactory, $view);

/*
 * Execute the necessary command on the controller
 */
$command = $request->getCommand();
$controller->{$command}($request);

/*
 * Produces the response
 */
echo $view->render();