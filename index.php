<?php

include 'vendor/autoload.php';

$container = new \Pimple\Container;

$container['path.base'] = (string) __DIR__ . '/';
$container['ns.mapper'] = '\\Mwyatt\\Core\\Mapper\\';
$container['ns.model'] = '\\Mwyatt\\Core\\Model\\';

$container['database'] = function ($container) {
    return new \Mwyatt\Core\Database\Pdo(include $container['path.base'] . 'config.php');
};

$container['cache'] = function ($container) {
    return new \Mwyatt\Core\Cache('example-name');
};

$cache = $container['cache'];
$cache->setKey('ok');

echo '<pre>';
print_r($cache);
print_r($container['cache']);
echo '</pre>';

$cache->setKey('nar');

echo '<pre>';
print_r($container['cache']);
echo '</pre>';
exit;



try {
    $database = $container['database'];
} catch (Exception $e) {
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    exit;
    
}

echo '<pre>';
print_r($database);
echo '</pre>';
exit;






$mapperFactory = new \Mwyatt\Core\MapperFactory($database);
$mapperFactory->setDefaultNamespace($container['ns.mapper']);

$modelFactory = new \Mwyatt\Core\ModelFactory;
$modelFactory->setDefaultNamespace($container['ns.model']);

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
