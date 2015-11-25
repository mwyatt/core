<?php

namespace Mwyatt\Core;

/**
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 * @version     0.1
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class Mail implements \Mwyatt\Core\MailInterface
{


    /**
     * \Swift_Mailer instance
     * @var object
     */
    protected $swiftMailer;


    /**
     * do we log in the database when sending
     * @var boolean
     */
    public $log = true;

    
    /**
     * get existing swiftmail transport instance or login and make new one
     * either way it will be passed to swiftMailer property
     */
    public function __construct()
    {
        $registry = \Mwyatt\Core\Registry::getInstance();

        // check for existing transport to skip login
        if (!$swiftMailer = $registry->get('swiftMailer')) {

            // get the config
            $config = $registry->get('config');
            
            // mail transport login
            $transport = \Swift_SmtpTransport::newInstance(
                $config['mail.host'],
                $config['mail.port'],
                $config['mail.security']
            );
            $transport->setUsername($config['mail.username']);
            $transport->setPassword($config['mail.appPassword']);
            $swiftMailer = \Swift_Mailer::newInstance($transport);
        }
        $this->setSwiftMailer($swiftMailer);
    }


    /**
     * sets body after mashing in inline styles
     * this is the method for doing tagging, simple now!
     * @return string inlined stuffs 
     */
    public function getInlinedHtml($body)
    {
        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
        $inliner->setHtml($body);
        $inliner->setUseInlineStylesBlock();
        return $inliner->convert();
    }
    
    
    /**
     * @param object $swiftMailer
     */
    private function setSwiftMailer(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
        return $this;
    }


    /**
     * configures headers and sends mail out
     * @param  array  $properties see requiredSendProperties for rules
     * @return bool
     */
    public function send(array $config)
    {

        // inline right away
        $config['body'] = $this->getInlinedHtml($config['body']);

        // resource
        $mailer = $this->swiftMailer;
        $message = \Swift_Message::newInstance($config['subject'])
            ->setFrom($config['from'])
            ->setTo($config['to'])
            ->addPart($config['body'], 'text/html')
            ->setBody($config['body']);

        // send and log possibly
        if ($result = $mailer->send($message) && $this->log) {
            $this->log($config);
        }
        return $result;
    }


    /**
     * only possible to log while sending an email
     * how will from and to addresses work?
     * @param  array  $config 
     * @return array created ids
     */
    private function log(array $config)
    {
        $mailEntity = new \Mwyatt\Core\Entity\Mail;
        $mailEntity->from = implode(' ', $config['from']);
        $mailEntity->to = implode(' ', $config['to']);
        $mailEntity->subject = $config['subject'];
        $mailEntity->body = $config['body'];
        $mailEntity->timeSent = time();
        $mailModel = new \Mwyatt\Core\Model\Mail;
        $mailModel->create([$mailEntity]);
        return $mailModel->getData();
    }
}

// email address table which uses email address as id
// users table could use email address as id?
