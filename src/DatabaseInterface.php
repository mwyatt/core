<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface DatabaseInterface
{


	/**
	 * @param array $credentials
	 */
	public function setCredentials(array $credentials);


    /**
     * connects to the database
     * @return bool success
     */
    public function connect();
}
