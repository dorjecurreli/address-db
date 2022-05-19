<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\PersonLabel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonLabelFixtures extends Fixture
{
    public const FIRST_PERSON_LABEL_REFERENCE = 'first-person-label';

    public function load(ObjectManager $manager): void
    {

        $firstPersonLabel = new PersonLabel();
        $firstPersonLabel->setIsContactable(1);
        $firstPersonLabel->setIsBlacklisted(0);
        $firstPersonLabel->setIsVIP(1);

//        $firstPersonLabel->setPerson($this->getReference(PersonFixtures::FIRST_PERSON_REFERENCE));
//        $firstPersonLabel->setLabel($this->getReference(LabelFixtures::FIRST_LABEL_REFERENCE));

        $secondPersonLabel = new PersonLabel();
        $secondPersonLabel->setIsContactable(0);
        $secondPersonLabel->setIsBlacklisted(1);
        $secondPersonLabel->setIsVIP(0);
//        $secondPersonLabel->setPerson($this->getReference(PersonFixtures::SECOND_PERSON_REFERENCE));
//        $secondPersonLabel->setLabel($this->getReference(LabelFixtures::SECOND_LABEL_REFERENCE));

        $manager->persist($firstPersonLabel);
        $manager->persist($secondPersonLabel);


        $manager->flush();
    }
}
