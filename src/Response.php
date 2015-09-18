<?php
namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Response implements \Mwyatt\Core\ResponseInterface
{


    /**
     * @var string
     */
    private $content;


    /**
     * @var int
     */
    private $statusCode;


    /**
     * build response object when in controller
     * @param string  $content
     * @param integer $statusCode
     */
    public function __construct($content = '', $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    
    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
