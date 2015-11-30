<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Controller implements \Mwyatt\Core\ControllerInterface
{


    protected $url;


    protected $database;


    protected $view;


    public function __construct(
        \Mwyatt\Core\DatabaseInterface $database,
        \Mwyatt\Core\ViewInterface $view,
        \Mwyatt\Core\UrlInterface $url
    )
    {
        $this->database = $database;
        $this->view = $view;
        $this->url = $url;
    }


    /**
     * redirects the user to another url and terminates
     * utilising the generator from symfony
     * could this be a static function?
     * @param  string $key      routeKey
     * @param  array $config if required
     * @return null
     */
    public function redirect($key, $config = [])
    {

        // generate string to redirect to from url
        // redirect
        header('location:' . $this->url->generate($key, $config));

        // always prevent continuation
        exit;
    }
}
