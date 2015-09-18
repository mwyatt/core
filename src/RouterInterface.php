<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface RouterInterface
{


    /**
     * obtains response object from matched controller
     * falls back to 404, or hits 500
     * @param  string $path
     * @return string       response content
     */
    public function getResponse($path);


    /**
     * add to the definition registry
     * @param  string $path to the new definitions array
     * @return null
     */
    public function appendRoutes(array $routes);
}
