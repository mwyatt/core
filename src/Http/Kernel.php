<?php

namespace Mwyatt\Core\Http;

class Kernel
{
    private $projectPath;
    private $pimple;

    
    public function __construct($projectPath)
    {
        $this->projectPath = $projectPath;
        $this->setPimple();
    }


    /**
     * setup all pimple services
     * should errorhandler always be like this?
     */
    public function setPimple()
    {
        $pimple = new \Pimple\Container;

        $pimple['ProjectPath'] = $this->projectPath;

        $pimple['Config'] = function ($pimple) {
            return new \Mwyatt\Core\Http\Config(include $pimple['ProjectPath'] . 'config.php');
        };

        $pimple['ErrorHandler'] = function ($pimple) {
            $config = $pimple['Config'];
            $log = new \Monolog\Logger('system');
            if ($config->getSetting('core.displayErrors')) {
                // ini_set('display_errors', 1);
                // ini_set('display_startup_errors', 1);
                // error_reporting(E_ALL);
                $log->pushHandler(new \Monolog\Handler\BrowserConsoleHandler);
            }
            $log->pushHandler(new \Monolog\Handler\StreamHandler($pimple['ProjectPath'] . 'error.txt', \Monolog\Logger::DEBUG));
            return $log;
        };

        $pimple['Routes'] = function ($pimple) {
            $url = $pimple['Url'];
            $routes = [];
            include $pimple['ProjectPath'] . 'routes.php';
            return $routes;
        };

        $pimple['Router'] = function ($pimple) {
            $router = new \Mwyatt\Core\Router(new \Pux\Mux);
            $router->appendMuxRoutes($pimple['Routes']);
            return $router;
        };

        $pimple['Request'] = function ($pimple) {
            session_start();
            return new \Mwyatt\Core\Request;
        };

        $pimple['View'] = function ($pimple) {
            $view = new \Mwyatt\Core\View;
            $view->appendTemplateDirectory($pimple['ProjectPath']);
            return $view;
        };

        $pimple['Url'] = function ($pimple) {
            $router = $pimple['Router'];
            $request = $pimple['Request'];
            $config = $pimple['Config'];
            $url = new \Mwyatt\Core\Url($request->getServer('HTTP_HOST'), $request->getServer('REQUEST_URI'), $config->getSetting('core.installDirectory'));
            $url->setRoutes($router->getMux());
            return $url;
        };

        $pimple['Mail'] = function ($pimple) {
            $config = $pimple['Config'];
            $transport = \Swift_SmtpTransport::newInstance(
                $config['mail.host'],
                $config['mail.port'],
                $config['mail.security']
            );
            $transport->setUsername($config['mail.username']);
            $transport->setPassword($config['mail.appPassword']);
            $swiftMailer = \Swift_Mailer::newInstance($transport);
            return new \Mwyatt\Core\Mail($swiftMailer);
        };

        $pimple['Database'] = function ($pimple) {
            $config = $pimple['Config'];
            return new \Mwyatt\Core\Database\Pdo($config);
        };

        $pimple['Thumb'] = function ($pimple) {
            $view = $pimple['View'];
            return new \Mwyatt\Elttl\Service\Thumb($view);
        };

        $pimple['Options'] = function ($pimple) {
            $modelOption = new \Mwyatt\Elttl\Model\Option;
            $modelOption->read();
            $options = $modelOption->getData();
            $optionKeyValue = [];
            foreach ($options as $option) {
                $optionKeyValue[$option->name] = $option->value;
            }
            return $optionKeyValue;
        };

        return $pimple;




        include $pimple['ProjectPath'] . 'pimple.php';
        \Monolog\ErrorHandler::register($pimple['ErrorHandler']);
        $this->pimple = $pimple;
    }
}
