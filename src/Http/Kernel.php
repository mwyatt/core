<?php

namespace Mwyatt\Core\Http;

/**
 * incorporate maintenance mode, how?
 */
class Kernel
{
    private $services;
    private $middleware = [];
    private $middlewarePost = [];

    
    public function __construct($projectPath)
    {
        $this->services = new \Pimple\Container;
        $this->services['ProjectPath'] = $projectPath;
        $this->registerServicesGlobal();
    }


    public function setRoutes(array $routes)
    {
        $this->services['Routes'] = $routes;
    }


    public function registerMiddleware($config)
    {
        $this->middleware = $config;
    }


    public function registerSettings(array $settings)
    {
        $config = $this->services['Config'];
        foreach ($settings as $key => $value) {
            $config->setSetting($key, $value);
        }
    }


    public function registerServicesOptional()
    {
        $this->services['ErrorHandler'] = function ($services) {
            $config = $services['Config'];
            $log = new \Monolog\Logger('system');
            if ($config->getSetting('core.displayErrors')) {
                // ini_set('display_errors', 1);
                // ini_set('display_startup_errors', 1);
                // error_reporting(E_ALL);
                $log->pushHandler(new \Monolog\Handler\BrowserConsoleHandler);
            }
            $log->pushHandler(new \Monolog\Handler\StreamHandler($services['ProjectPath'] . 'error.txt', \Monolog\Logger::DEBUG));
            return $log;
        };

        \Monolog\ErrorHandler::register($this->services['ErrorHandler']);

        $this->services['ModelFactory'] = function ($services) {
            $config = $services['Config'];
            $modelFactory = new \Mwyatt\Core\Factory\Model;
            $modelFactory->setDefaultNamespace($config->getSetting('model.factory.namespace'));
            return $modelFactory;
        };

        $this->services['IteratorFactory'] = function ($services) {
            $config = $services['Config'];
            $iteratorFactory = new \Mwyatt\Core\Factory\Iterator;
            $iteratorFactory->setDefaultNamespace($config->getSetting('iterator.factory.namespace'));
            return $iteratorFactory;
        };

        $this->services['MapperFactory'] = function ($services) {
            $mapperFactory = new \Mwyatt\Core\Factory\Mapper(
                $services['Database'],
                $services['ModelFactory'],
                $services['IteratorFactory']
            );
            $mapperFactory->setDefaultNamespace($config->getSetting('mapper.factory.namespace'));
            return $mapperFactory;
        };

        $this->services['RepositoryFactory'] = function ($services) {
            $config = $services['Config'];
            $repositoryFactory = new \Mwyatt\Core\Factory\Repository($services['MapperFactory']);
            $repositoryFactory->setDefaultNamespace($config->getSetting('repository.factory.namespace'));
            return $repositoryFactory;
        };

        $this->services['Mail'] = function ($services) {
            $config = $services['Config'];
            $transport = \Swift_SmtpTransport::newInstance(
                $config->getSetting('mail.host'),
                $config->getSetting('mail.port'),
                $config->getSetting('mail.security')
            );
            $transport->setUsername($config->getSetting('mail.username'));
            $transport->setPassword($config->getSetting('mail.appPassword'));
            $swiftMailer = \Swift_Mailer::newInstance($transport);
            return new \Mwyatt\Core\Mail($swiftMailer);
        };

        $this->services['Database'] = function ($services) {
            $config = $services['Config'];
            $database = new \Mwyatt\Core\Database\Pdo;
            $database->connect(
                $config->getSetting('database.host'),
                $config->getSetting('database.basename'),
                $config->getSetting('database.username'),
                $config->getSetting('database.password')
            );
            return $database;
        };
    }


    public function registerServices(array $services = [])
    {
        foreach ($services as $key => $value) {
            
        }
    }


    private function registerServicesGlobal()
    {
        $this->services['Config'] = function ($services) {
            return new \Mwyatt\Core\Http\Config(include $services['ProjectPath'] . 'config.php');
        };

        $this->services['Router'] = function ($services) {
            $router = new \Mwyatt\Core\Router(new \Pux\Mux);
            $router->appendRoutes($services['Routes']);
            return $router;
        };

        $this->services['Route'] = function ($services) {
            $router = $services['Router'];
            $url = $services['Url'];
            return $router->getMatch('/' . $url->getPath());
        };

        $this->services['Request'] = function ($services) {
            $request = new \Mwyatt\Core\Request(
                new \Mwyatt\Core\Session, 
                new \Mwyatt\Core\Cookie
            );
            return $request;
        };

        $this->services['ControllerError'] = function ($services) {
            return new \Mwyatt\Core\Controller\Error(
                $services,
                $services['View']
            );
        };

        $this->services['Url'] = function ($services) {
            $routes = $services['Routes'];
            $request = $services['Request'];
            $config = $services['Config'];
            $url = new \Mwyatt\Core\Url(
                $request->getServer('HTTP_HOST'),
                $request->getServer('REQUEST_URI'),
                $config->getSetting('core.installDirectory')
            );
            $url->setRoutes($routes);
            return $url;
        };

        $this->services['View'] = function ($services) {
            $url = $services['Url'];
            $view = new \Mwyatt\Core\View($services['ProjectPath'] . 'template/');
            $view->appendTemplateDirectory((string) (__DIR__ . '/../../') . 'template/');

            // global view values
            // better place for this?
            $view->offsetSet('url', $url);

            return $view;
        };
    }


    private function runMiddleware(array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $this->middleware)) {
                $className = $this->middleware[$key];
                $middleware = new $className(
                    $this->services,
                    $this->services['View']
                );
                $middleware->handle($this->services['Request']);
            }
        }
    }


    private function runMiddlewarePost()
    {
    }


    public function route()
    {
        if (!headers_sent()) {
            session_start();
        }

        $config = $this->services['Config'];
        $routes = $this->services['Routes'];
        $url = $this->services['Url'];
        $request = $this->services['Request'];
        $router = $this->services['Router'];
        $route = $this->services['Route'];
        $view = $this->services['View'];
        $controllerError = $this->services['ControllerError'];
        $response = false;

        if ($config->getSetting('core.maintenance') || !$routes) {
            $response = $controllerError->maintenance($request);
        } elseif ($route) {
            try {
                $request->setMuxUrlVars($route);

                if (!empty($route[3]['middleware'])) {
                    $this->runMiddleware($route[3]['middleware']);
                }

                $controllerName = $router->getRouteControllerName($route);
                if (!class_exists($controllerName)) {
                    throw new \Exception("Controller '$controllerName' does not exist.");;
                }
                $controllerMethod = $router->getRouteControllerMethod($route);
                $controller = new $controllerName(
                    $this->services,
                    $view
                );
                if (!method_exists($controller, $controllerMethod)) {
                    throw new \Exception("Controller method '$controllerMethod' does not exist.");;
                }
                $response = $controller->$controllerMethod($request);
            } catch (\Exception $e) {
                $response = $controllerError->e500();
            }
        } else {
            $response = $controllerError->e404();
        }

        http_response_code($response->getStatusCode());
        echo $response->getContent();

        $this->runMiddlewarePost();
    }
}
