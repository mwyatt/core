<?php

namespace Mwyatt\Core;

class Mail implements \Mwyatt\Core\MailInterface
{


    /**
     * \Swift_Mailer instance
     * @var object
     */
    protected $swiftMailer;


    /**
     * get existing swiftmail transport instance or login and make new one
     * either way it will be passed to swiftMailer property
     */
    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }
    
    
    /**
     * get swiftmail message instance
     * @return object 
     */
    public function getNewMessage()
    {
        return \Swift_Message::newInstance();
    }


    /**
     * inline html then send out the mail
     * @return bool
     */
    public function send($message)
    {
        return $this->swiftMailer->send($message);
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
}
