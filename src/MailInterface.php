<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
interface MailInterface
{


    /**
     * configures headers and sends mail out
     * @param  array  $properties see requiredSendProperties for rules
     * @return bool
     */
    public function send(array $config);
}
