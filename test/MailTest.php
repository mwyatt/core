<?php

namespace Mwyatt\Core;

class MailTest extends \PHPUnit_Framework_TestCase
{


    public function testSendNoLog()
    {
        $registry = \Mwyatt\Core\Registry::getInstance();
        $config = include (string) (__DIR__ . '/../config.php');
        $registry->set('config', $config);
        $mail = new \Mwyatt\Core\Mail;
        $mail->log = false;
        $result = $mail->send([
            'subject' => 'Reset Password',
            'from' => [$config['mail.username'] => 'Martin Wyatt'],
            'to' => [$config['mail.username']],
            'body' => '<h1>example body</h1>'
        ]);
        $this->assertTrue($result);
    }
}
