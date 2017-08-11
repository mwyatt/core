<?php

namespace Mwyatt\Core;

class IteratorCollectionTest extends \PHPUnit_Framework_TestCase
{
    private $users;
    private $numbers;


    public function setUp()
    {
        $users = [
        [
        'id' => 1,
        'email' => 'example@exmple.com',
        'nameFirst' => 'Martin',
        'nameLast' => 'Wyatt',
        'timeCreated' => 1474113942,
        'password' => 'ojfdgodfig'
        ],
        [
        'id' => 2,
        'email' => 'example@exmple.coms',
        'nameFirst' => 'Steve',
        'nameLast' => 'Smith',
        'timeCreated' => 1474113942,
        'password' => 'ojfdgodfig'
        ],
        [
        'id' => 3,
        'email' => 'example@exmple.comss',
        'nameFirst' => 'Alan',
        'nameLast' => 'Appleby',
        'timeCreated' => 1474113942123,
        'password' => 'ojfdgodfigsss'
        ],
        ];
        $items = [];
        foreach ($users as $user) {
            $item = new \Mwyatt\Core\Model\User;
            $item->setId($user['id']);
            $item->setEmail($user['email']);
            $item->setNameFirst($user['nameFirst']);
            $item->setNameLast($user['nameLast']);
            $item->setPassword($user['password']);
            $items[] = $item;
        }
        $this->users = new \Mwyatt\Core\Iterator\Collection($items);
        $this->numbers = new \Mwyatt\Core\Iterator\Collection([1, 2, 3, 4, 5]);
        $this->assoc = new \Mwyatt\Core\Iterator\Collection([
            ['name' => 'Bill', 'age' => 20],
            ['name' => 'Peter', 'age' => 10],
            ['name' => 'Bill', 'age' => 45],
        ]);
    }


    public function testGetFirst()
    {
        $this->numbers->next();
        $this->numbers->next();
        $this->numbers->next();
        $item = $this->numbers->getFirst();
        $this->assertTrue($item === 1);
    }


    public function testGetLast()
    {
        $item = $this->numbers->getLast();
        $this->assertTrue($item == 5);
    }


    public function testPluck()
    {
        $values = $this->users->pluck('nameFirst');
        $this->assertTrue($values[0] === 'Martin');
        $this->assertTrue($values[1] === 'Steve');

        $values = $this->assoc->pluck('name');
        $this->assertTrue($values[0] == 'Bill');
        
        // unique
        $values = $this->assoc->pluckUnique('name');
        $this->assertTrue($values->count() == 2);

        $values = $this->users->pluckUnique('timeCreated');
        $this->assertTrue(count($values) === 1);
    }


    public function testGetByPropertyValues()
    {
        $users = $this->users->getByPropertyValues('nameFirst', ['Martin', 'David']);
        $item = $users->current();
        $this->assertTrue($item->nameFirst === 'Martin');

        // strict
        $users = $this->users->getByPropertyValuesStrict('id', [true]);
        $this->assertTrue($users->count() === 0);
        $users = $this->users->getByPropertyValuesStrict('id', [1]);
        $this->assertTrue($users->count() === 1);
    }


    public function testKeys()
    {
        $keys = $this->users->keys();
        $this->assertTrue($keys[0] === 0);
        $this->assertTrue($keys[1] === 1);
    }


    public function testGetKeyedByProperty()
    {
        $keyedByNameLast = $this->users->getKeyedByProperty('nameLast');
        $keys = $keyedByNameLast->keys();
        $this->assertTrue($keys[0] === 'Wyatt');
        $this->assertTrue($keys[1] === 'Smith');
    }


    public function testSort()
    {
        $users = $this->users->sort();
        $users = $this->users->sort(function ($a, $b) {
            return strcasecmp($a->nameLast, $b->nameLast);
        });
        $this->assertTrue($users->current()->nameLast === 'Appleby');
        $users->next();
        $this->assertTrue($users->current()->nameLast === 'Smith');

        // reset keys
        $users->resetKeys();
        $this->assertTrue(key($users->current()) == 0);
    }


    public function testMap()
    {
        $users = $this->users->map(function ($item) {
            return $item->id + 10;
        });
        $this->assertTrue($users->current() === 11);
        $this->assertTrue($users->current() === 11);
    }


    public function testAdd()
    {
        $item = $this->users->append('hello')->getLast();
        $this->assertTrue($item === 'hello');
    }


    public function testNext()
    {
        $item = $this->users->next();
        $this->assertTrue($item->nameFirst == 'Steve');
    }


    public function testFilter()
    {
        $users = $this->users->append('');
        $this->assertTrue($users->count() === 4);
        
        $users = $users->filter();
        $this->assertTrue($users->count() === 3);

        $users = $users->filter(function ($item) {
            return $item->nameFirst === 'Martin';
        });
        $this->assertTrue($users->count() === 1);
        $this->assertTrue($users->current()->nameFirst === 'Martin');
    }


    public function testJsonEncode()
    {
        $json = json_encode($this->users);
        $this->assertTrue($json === '[{"nameFirst":"Martin","nameLast":"Wyatt","email":"example@exmple.com"},{"nameFirst":"Steve","nameLast":"Smith","email":"example@exmple.coms"},{"nameFirst":"Alan","nameLast":"Appleby","email":"example@exmple.comss"}]');
    }


    public function testOffsetAppend()
    {
        // $this->
    }
}
