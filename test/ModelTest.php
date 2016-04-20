<?php

namespace Mwyatt\Core;

class ModelTest extends \PHPUnit_Framework_TestCase
{


    public $exampleUserData = [
        'email' => 'martin.wyatt@gmail.com',
        'password' => '123123123',
        'timeRegistered' => 129038190382392,
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];


    public function testCreate()
    {
        $user = new \Mwyatt\Core\Model\User;
    }


    public function testSetPass()
    {
        $user = new \Mwyatt\Core\Model\User;
        $user->setEmail($this->exampleUserData['email']);
        $this->assertEquals($user->email, $this->exampleUserData['email']);
    }


    /**
     * @expectedException \Exception
     */
    public function testSetFail()
    {
        $user = new \Mwyatt\Core\Model\User;
        $user->setEmail('failureemail.com');
    }
}
