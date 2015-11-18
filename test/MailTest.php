<?php

namespace Mwyatt\Core;

class MailTest extends \PHPUnit_Framework_TestCase
{


    public function testSend()
    {
        $registry = \Mwyatt\Core\Registry::getInstance();
        $config = include '../config.php';
        $registry->set('config', $config);
        $mail = new \Mwyatt\Core\Mail;
        $result = $mail->send([
            'subject' => 'Reset Password',
            'from' => [$config['mail.username'] => 'Martin Wyatt'],
            'to' => [$config['mail.username']],
            'body' => $body
        ]);
        $this->assertTrue($result);
    }
}
