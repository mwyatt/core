<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Controller implements \Mwyatt\Core\ControllerInterface
{


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
}
