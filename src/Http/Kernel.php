<?php

namespace Mwyatt\Core\Http;

class Kernel implements \Mwyatt\Core\Http\KernelInterface
{
    private $services;
    private $middleware = [];
    private $middlewarePost = [];

    
    public function __construct()
    {
        $this->services = new \Pimple\Container;
    }


    public function setRoutes(array $routes)
    {
        $this->services['Routes'] = $routes;
    }


    public function setMiddleware($config)
    {
        $this->middleware = $config;
    }


    public function setSettings(array $settings)
    {
        $config = $this->services['Config'];
        foreach ($settings as $key => $value) {
            $config->setSetting($key, $value);
        }
    }


    public function setServicesOptional()
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

        \Monolog\ErrorHandler::set($this->services['ErrorHandler']);

        $this->services['ModelFactory'] = function ($services) {
            $config = $services['Config'];
            $modelFactory = new \Mwyatt\Core\Factory\Model;
            $modelFactory->setDefaultNamespace($config->getSetting('projectBaseNamespace') . 'Model\\');
            return $modelFactory;
        };

        $this->services['IteratorFactory'] = function ($services) {
            $config = $services['Config'];
            $iteratorFactory = new \Mwyatt\Core\Factory\Iterator;
            $iteratorFactory->setDefaultNamespace($config->getSetting('projectBaseNamespace') . 'Iterator\\');
            return $iteratorFactory;
        };

        $this->services['MapperFactory'] = function ($services) {
            $config = $services['Config'];
            $mapperFactory = new \Mwyatt\Core\Factory\Mapper(
                $services['Database'],
                $services['ModelFactory'],
                $services['IteratorFactory']
            );
            $mapperFactory->setDefaultNamespace($config->getSetting('projectBaseNamespace') . 'Mapper\\');
            return $mapperFactory;
        };

        $this->services['RepositoryFactory'] = function ($services) {
            $config = $services['Config'];
            $repositoryFactory = new \Mwyatt\Core\Factory\Repository($services['MapperFactory']);
            $repositoryFactory->setDefaultNamespace($config->getSetting('projectBaseNamespace') . 'Repository\\');
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


    public function setServices($path)
    {
        if (!file_exists($path)) {
            throw new \Exception("Path '$path' does not exist.");
        }
        include $path;
    }


    public function setServiceProjectPath($projectPath)
    {
        $this->services['ProjectPath'] = $projectPath;
    }


    public function setServicesEssential()
    {
        $this->services['Config'] = function ($services) {
            return new \Mwyatt\Core\Http\Config(include $services['ProjectPath'] . 'config.php');
        };

        $this->services['Router'] = function ($services) {
            $router = new \Mwyatt\Core\Router(new \Pux\Mux);
            $router->appendRoutes($services['Routes']);
            return $router;
        };

        $this->services['Template'] = function ($services) {
            $template = new \Mwyatt\Core\Template(
                $services['ProjectPath'],
                $services['View']
            );
            return $template;
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
            $view = new \Mwyatt\Core\View($services['ProjectPath'] . 'template/');
            $view->appendTemplateDirectory((string) (__DIR__ . '/../../') . 'template/');
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
        $config = $this->services['Config'];
        try {
            $this->doRoute();
        } catch (\Exception $e) {
            echo 'Kernel Error';
            if ($config->getSetting('core.displayErrors')) {
                echo ': ' . $e->getMessage();
            }
        }
    }


    private function doRoute()
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
        $controllerError = new \Mwyatt\Core\Controller\Error(
            $this->services,
            $view
        );
        $response = false;

        $view->offsetSet('config', $config);
        $view->offsetSet('url', $url);
        $view->offsetSet('template', $this->services['Template']);

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
                    throw new \Exception("Controller '$controllerName' does not exist.");
                    ;
                }
                $controllerMethod = $router->getRouteControllerMethod($route);
                $controller = new $controllerName(
                    $this->services,
                    $view
                );
                if (!method_exists($controller, $controllerMethod)) {
                    throw new \Exception("Controller method '$controllerMethod' does not exist.");
                    ;
                }
                $response = $controller->$controllerMethod($request);
            } catch (\Exception $e) {
                if ($config->getSetting('core.displayErrors')) {
                    $message = $e->getMessage();
                } else {
                    $message = '';
                }
                $response = $controllerError->e500($message);
            }
        } else {
            $response = $controllerError->e404();
        }

        http_response_code($response->getStatusCode());
        echo $response->getContent();

        $this->runMiddlewarePost();
    }
}
