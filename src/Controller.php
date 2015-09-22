<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Controller implements \Mwyatt\Core\ControllerInterface
{


    protected $view;


    public function __construct()
    {
        $this->view = new \Mwyatt\Core\View;
    }



    public function home()
    {
        return new Response(200);
    }


    public function e500(\Exception $exception)
    {
        return new Response(500, 500);
    }


    public function e404(\Mwyatt\Core\ResponseInterface $response)
    {
        return new Response(404, 404);
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

        // get url from registry and generate string
        $registry = \Mwyatt\Core\Registry::getInstance();
        $url = $registry->get('url');
        $url = $url->generate($key, $config);

        // redirect
        header('location:' . $url);

        // prevent continuation
        // do you need this if you have structured the app correctly?
        exit;
    }
}
