<?php

namespace Mwyatt\Core;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    protected $modelFactory;
    protected $userModelDataGood = [
        'email' => 'martin.wyatt@gmail.com',
        'timeRegistered' => '8123927829',
        'password' => '123123123',
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];
    protected $userModelDataBad = [
        'email' => 'martinwyattgmailcom',
        'timeRegistered' => '8123927829',
        'password' => '123123123',
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt'
    ];


    public function setUp()
    {
        $this->modelFactory = new \Mwyatt\Core\Factory\Model;
    }


    public function testSetPass()
    {
        $user = $this->modelFactory->get('User', $this->userModelDataGood);
    }


    /**
     * @expectedException \Exception
     */
    public function testSetFail()
    {
        $user = $this->modelFactory->get('User', $this->userModelDataBad);
    }


    public function testGet()
    {
        $user = $this->modelFactory->get('User', $this->userModelDataGood);
        $this->assertEquals($user->get('email'), $this->userModelDataGood['email']);
    }
}
