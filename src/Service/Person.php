<?php

namespace Mwyatt\Core\Service;

/**
 * always returns domain objects?
 * @author Martin Wyatt <martin.wyatt@gmail.com>
 */
class Person extends \Mwyatt\Core\ServiceAbstract
{


    public function getById($ids)
    {
        $mapperPerson = $this->mapperFactory->get('Person');
        $people = $mapperPerson->readColumn($ids);
        $people = $this->bindAddresses($people);


        return $modelPeople;
        
    }


    public function getAll()
    {
        $mapperPerson = $this->mapperFactory->get('Person');


        $people = $mapperPerson->read();
        $people = $this->bindBasics($people);
        return $people;
    }


    public function bindBasics($people)
    {
        $models = [];
        foreach ($people as $person) {
            $model = $this->modelFactory->get('Person');
            $names = explode(' ', $person->name);
            $model->nameFirst = reset($names);
            $model->nameLast = end($names);
        }
        return $models;
    }


    public function bindAddresses($people)
    {
        $personIds = $mapperPerson->getDataProperty('id');
        $mapperAddress = $this->mapperFactory->get('Address');
        $addresses = $mapperAddress->readColumn($personIds, 'personId');
        $mapperAddress->keyDataByPropertyMulti('personId');

        $modelPeople = [];
        foreach ($people as $person) {
            $modelPerson = $this->modelFactory->get('Person');
            $modelPerson->addresses = $mapperAddress->getDataKey($person->id);
            $modelPeople[] = $modelPerson;
        }

        return $modelPeople;
    }


    public function create($name, $telephoneLandline)
    {
        $mapperPerson = $this->mapperFactory->get('Person');
        $person = $this->modelFactory->get('Person');
        $person->setName($name);
        $mapperPerson->create([$person]);
    }
}
