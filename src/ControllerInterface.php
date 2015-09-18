<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface ControllerInterface
{


    public function home();


    public function e500(\Exception $exception);


    public function e404(\Mwyatt\Core\ResponseInterface $response);
}
