<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{

    public const FIRST_PERSON_REFERENCE = 'first-person';
    public const SECOND_PERSON_REFERENCE = 'second-person';

    public function load(ObjectManager $manager): void
    {
        $firstPerson = new Person();
        $firstPerson->setFirstName('Steve');
        $firstPerson->setLastName('Jobs');
        $firstPerson->setAddress($this->getReference(AddressFixtures::FIRST_ADDRESS_REFERENCE));
        $firstPerson->setSalutation("I'm the first Person");
        $this->addReference(self::FIRST_PERSON_REFERENCE, $firstPerson);

        $secondPerson = new Person();
        $secondPerson->setFirstName('Willi');
        $secondPerson->setLastName('Wonka');
        $secondPerson->setAddress($this->getReference(AddressFixtures::SECOND_ADDRESS_REFERENCE));
        $secondPerson->setSalutation("I'm the second Person");
        $this->addReference(self::SECOND_PERSON_REFERENCE, $secondPerson);

        $manager->persist($firstPerson);
        $manager->persist($secondPerson);

        $manager->flush();
    }
}
