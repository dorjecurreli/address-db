<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{

    public const FIRST_ADDRESS_REFERENCE = 'first-address';
    public const SECOND_ADDRESS_REFERENCE = 'second-address';
    public const THIRD_ADDRESS_REFERENCE = 'third-address';

    public function load(ObjectManager $manager): void
    {
        $firstAddress = new Address();
        $secondAddress = new Address();
        $thirdAddress = new Address();

        $firstAddress->setCountry('Germany');
        $firstAddress->setRegion('Berlin');
        $firstAddress->setCity('Berlin');
        $firstAddress->setStreet('Teststr.');
        $firstAddress->setHouseNumber('12');
        $firstAddress->setPostalCode('12345');

        $secondAddress->setCountry('Italy');
        $secondAddress->setRegion('Sardegna');
        $secondAddress->setCity('Cagliari');
        $secondAddress->setStreet('Via Test');
        $secondAddress->setHouseNumber('13');
        $secondAddress->setPostalCode('07021');

        $thirdAddress->setCountry('France');
        $thirdAddress->setRegion('Normandie');
        $thirdAddress->setCity('Paris');
        $thirdAddress->setStreet('Francestreet');
        $thirdAddress->setHouseNumber('31');
        $thirdAddress->setPostalCode('3214');

        $this->setReference(self::FIRST_ADDRESS_REFERENCE, $firstAddress);
        $this->setReference(self::SECOND_ADDRESS_REFERENCE, $secondAddress);
        $this->setReference(self::THIRD_ADDRESS_REFERENCE, $thirdAddress);

        $manager->persist($firstAddress);
        $manager->persist($secondAddress);
        $manager->persist($thirdAddress);
        $manager->flush();


    }


}
