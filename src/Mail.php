<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mail
{


    /**
     * \Swift_Mailer instance
     * @var object
     */
    protected $swiftMailer;

    
    public function __construct()
    {
        $this->setAppPassword();
        
        // mail transport
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('martin.wyatt@gmail.com')
            ->setPassword($this->getAppPassword());
        $this->setSwiftMailer(\Swift_Mailer::newInstance($transport));
    }

/*
$mail->send([
    'subject' => 'Reset Password',
    'from' => ['admin@site.com' => 'Admin'],
    'to' => [$email],
    'body' => $body
]);

 */

    /**
     * sets body after mashing in inline styles
     * this is the method for doing tagging, simple now!
     * @return object 
     */
    public function setBody($body)
    {
        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
        $inliner->setHtml($body);
        $inliner->setUseInlineStylesBlock();
        $this->set_body($inliner->convert());
        return $this;
    }
    
    
    /**
     * @param object $swiftMailer
     */
    public function setSwiftMailer(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
        return $this;
    }


    /**
     * @return string
     */
    public function getAppPassword()
    {
        return $this->appPassword;
    }
    
    
    /**
     * @param string $appPassword
     */
    public function setAppPassword()
    {
        $registry = \OriginalAppName\Registry::getInstance();
        $config = $registry->get('config');
        $this->appPassword = $config['googleAppPassword'];
        return $this;
    }



    /**
     * configures headers and sends mail out
     * @param  array  $properties see requiredSendProperties for rules
     * @return bool
     */
    public function send($config)
    {

        // resource
        $mailer = $this->getSwiftMailer();
        $message = \Swift_Message::newInstance($config['subject'])

            // ['email' => 'contact name']
            ->setFrom($config['from'])

            // ['email', 'email']
            ->setTo($config['to'])

            // body
            ->addPart($config['body'], 'text/html')
            ->setBody($config['body']);

        // send
        $result = $mailer->send($message);

        // store
        if (! $result) {
            return;
        }

        // store
        $entityMail = new \OriginalAppName\Entity\Mail;
        $entityMail
            ->setFrom(implode(' ', $config['from']))
            ->setTo(implode(' ', $config['to']))
            ->setSubject($config['subject'])
            ->setBody($config['body'])
            ->setTimeSent(time());
        $model = new \OriginalAppName\Model\Mail;
        $model->create([$entityMail]);

        // positive
        return true;
    }
}
