<?php

namespace App\DataFixtures;

use App\Entity\Label;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LabelFixtures extends Fixture
{
    public const FIRST_LABEL_REFERENCE = 'first-label';
    public const SECOND_LABEL_REFERENCE = 'second-label';

    public function load(ObjectManager $manager): void
    {
        $firstLabel = new Label();
        $firstLabel->setName('First Label');
        $firstLabel->setColor('#F44336');
        $firstLabel->setDescription('red label society');
        $firstLabel->setIcon('fas fa-tag');
        $this->addReference(self::FIRST_LABEL_REFERENCE, $firstLabel);

        $secondLabel = new Label();
        $secondLabel->setName('Second Label');
        $secondLabel->setColor('#000000');
        $secondLabel->setDescription('black label society');
        $secondLabel->setIcon('fas fa-tag');
        $this->addReference(self::SECOND_LABEL_REFERENCE, $secondLabel);

        $manager->persist($firstLabel);
        $manager->persist($secondLabel);
        $manager->flush();
    }
}
