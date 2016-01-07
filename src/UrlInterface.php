<?php

namespace Mwyatt\Core;

/**
 * the aim of this class is to do all things url
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface UrlInterface
{
    public function __construct($host, $installPathQuery, $install = '');
    public function getPath();
    public function getQueryArray();
    public function setRoutes(\Pux\Mux $mux);
    public function generate($key = 'home', $config = []);
    public function generateVersioned($pathBase, $pathAppend);
}
