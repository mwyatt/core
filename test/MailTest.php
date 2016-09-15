<?php

namespace Mwyatt\Core;

class MailTest extends \PHPUnit_Framework_TestCase
{
    public $container;


    public function setUp()
    {
        $container = new \Pimple\Container;
        
        $container['local.config'] = function ($container) {
            return include (string) (__DIR__ . '/../') . 'config.php';
        };

        $container['swiftMailer'] = function ($container) {
            $config = $container['local.config'];

            $transport = \Swift_SmtpTransport::newInstance(
                $config['mail.host'],
                $config['mail.port'],
                $config['mail.security']
            );
            $transport->setUsername($config['mail.username']);
            $transport->setPassword($config['mail.appPassword']);

            return \Swift_Mailer::newInstance($transport);
        };

        $this->container = $container;
    }


    public function testSend()
    {
        $swiftMailer = $this->container['swiftMailer'];
        $config = $this->container['local.config'];
        $mail = new \Mwyatt\Core\Mail($swiftMailer);

        $message = $mail->getNewMessage();
        $message->setSubject('Reset Password');
        $message->setFrom([$config['mail.username'] => 'Martin Wyatt']);
        $message->setTo([$config['mail.username']]);
        $body = $mail->getInlinedHtml('<h1>PHPUnit testSend</h1><p>Just testing!</p>');
        $message->addPart($body, 'text/html');
        $message->setBody($body);

        $this->assertTrue(!empty($mail->send($message)));
    }
}
