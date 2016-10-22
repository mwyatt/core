<?php

namespace Mwyatt\Core\Http;


/**
 * incorporate maintenance mode, how?
 */
class Kernel
{
    private $projectPath;
    private $services;

    
    public function __construct($projectPath)
    {
        $this->projectPath = $projectPath;
        $this->setServices();
        $this->route();
    }


    /**
     * setup all pimple services
     * should errorhandler always be like this?
     */
    private function setServices()
    {
        $services = new \Pimple\Container;

        $services['ProjectPath'] = $this->projectPath;

        $services['Config'] = function ($services) {
            return new \Mwyatt\Core\Http\Config(include $services['ProjectPath'] . 'config.php');
        };

        $services['ErrorHandler'] = function ($services) {
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

        $services['Routes'] = function ($services) {
            $url = $services['Url'];
            $routes = [];
            include $services['ProjectPath'] . 'routes.php';
            return $routes;
        };

        $services['Router'] = function ($services) {
            $router = new \Mwyatt\Core\Router(new \Pux\Mux);
            $router->appendMuxRoutes($services['Routes']);
            return $router;
        };

        $services['Request'] = function ($services) {
            session_start();
            return new \Mwyatt\Core\Request;
        };

        $services['View'] = function ($services) {
            $view = $services['ViewFactory'];
            $view = new \Mwyatt\Core\View;
            $view->appendTemplateDirectory($services['ProjectPath']);
            return $view;
        };

        $services['ViewFactory'] = function ($services) {
            return new \Mwyatt\Core\Factory\View($services['DefaultTemplateDirectory']);
        };

        $services['Url'] = function ($services) {
            $router = $services['Router'];
            $request = $services['Request'];
            $config = $services['Config'];
            $url = new \Mwyatt\Core\Url($request->getServer('HTTP_HOST'), $request->getServer('REQUEST_URI'), $config->getSetting('core.installDirectory'));
            $url->setRoutes($router->getMux());
            return $url;
        };

        $services['Mail'] = function ($services) {
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

        $services['Database'] = function ($services) {
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

        include $services['ProjectPath'] . 'pimple.php';

        \Monolog\ErrorHandler::register($services['ErrorHandler']);

        $this->services = $services;
    }


    private function route()
    {
        $url = $this->services['Url'];
        $request = $this->services['Request'];
        $router = $this->services['Router'];
        $view = $this->services['View'];
        $routes = $this->services['Routes'];

        $route = $router->getMuxRouteCurrent('/' . $url->getPath());
        $request->setMuxUrlVars($route);
        
        // pre middleware?

        $controllerError = new \Mwyatt\Elttl\Controller\Error($this->services, $view);
        if ($route) {
            $controllerNs = '\\' . $router->getMuxRouteCurrentController();
            $controllerMethod = $router->getMuxRouteCurrentControllerMethod();
            $controller = new $controllerNs(
                $this->services,
                $view
            );
            try {
                $response = $controller->$controllerMethod($request);
            } catch (\Exception $e) {

                // server error 500
                // $response = $controllerError->server($e->getMessage());
            }
        } else {

            // undefined route error
            // $response = $controllerError->route();
        }

        http_response_code($response->getStatusCode());

        // allow way to set more headers?

        echo $response->getContent();
        

        // post middleware?


    }
}
