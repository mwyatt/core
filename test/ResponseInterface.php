<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface ResponseInterface
{


    /**
     * build response object when in controller
     * @param string  $content
     * @param integer $statusCode
     */
    public function __construct($content = '', $statusCode = 200);


    /**
     * @return string
     */
    public function getContent();
    

    /**
     * @return int
     */
    public function getStatusCode();


    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode);
}
