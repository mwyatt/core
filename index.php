<?php

include 'vendor/autoload.php';

$pathBase = (string) __DIR__ . '/';
$nsMapper = '\\Mwyatt\\Core\\Mapper\\';
$nsModel = '\\Mwyatt\\Core\\Model\\';


$container = new \Pimple\Container;

$container['database'] = function ($container) {
    $config = include $pathBase . 'config.php';

    $config = new \Doctrine\DBAL\Configuration();
    $connectionParams = array(
        'dbname' => 'test_1',
        'user' => 'root',
        'password' => '123',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    );
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

    $statement = $conn->prepare('SELECT * FROM shop_brands');
    $statement->execute();
    $brands = $statement->fetchAll();


    $huh = $conn->update('foo', array('name' => 'boop'), array('id' => 2));

    
    return new \Mwyatt\Core\Database\Pdo();
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
$mapperFactory->setDefaultNamespace($nsMapper);

$modelFactory = new \Mwyatt\Core\ModelFactory;
$modelFactory->setDefaultNamespace($nsModel);

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
