<?php

namespace Mwyatt\Core;

interface MailInterface
{
    public function __construct(\Swift_Mailer $swiftMailer);
    public function getNewMessage();
    public function send($message);
}
