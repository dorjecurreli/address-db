<?php

namespace App\DataFixtures;

use App\Entity\OrganizationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationTypeFixtures extends Fixture
{
    public const FIRST_ORGANIZATION_TYPE_REFERENCE = 'first-organization-type';
    public const SECOND_ORGANIZATION_TYPE_REFERENCE = 'second-organization-type';


    public function load(ObjectManager $manager): void
    {
        $firstOrganizationType = new OrganizationType();
        $firstOrganizationType->setName('Kita');
        $this->addReference(self::FIRST_ORGANIZATION_TYPE_REFERENCE, $firstOrganizationType);

        $secondOrganizationType = new OrganizationType();
        $secondOrganizationType->setName('School');
        $this->addReference(self::SECOND_ORGANIZATION_TYPE_REFERENCE, $secondOrganizationType);

        $manager->persist($firstOrganizationType);
        $manager->persist($secondOrganizationType);
        $manager->flush();
    }

}
