<?php

namespace App\DataFixtures;


use App\Entity\OrganizationOrganizationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationOrganizationTypeFixtures extends Fixture
{
    public const FIRST_ORGANIZATION_ORGANIZATION_TYPE_REFERENCE = 'first-organization-organization-type';
    public const SECOND_ORGANIZATION_ORGANIZATION_TYPE_REFERENCE = 'second-organization-organization-type';

    public function load(ObjectManager $manager): void
    {

        $firstOrganizationOrganizationType = new OrganizationOrganizationType();
//        $firstOrganizationOrganizationType->setOrganization($this->getReference(OrganizationFixtures::FIRST_ORGANIZATION_REFERENCE));
//        $firstOrganizationOrganizationType->setOrganizationType($this->getReference(OrganizationTypeFixtures::FIRST_ORGANIZATION_TYPE_REFERENCE));

        $secondOrganizationOrganizationType = new OrganizationOrganizationType();
//        $secondOrganizationOrganizationType->setOrganization($this->getReference(OrganizationFixtures::SECOND_ORGANIZATION_REFERENCE));
//        $secondOrganizationOrganizationType->setOrganizationType($this->getReference(OrganizationTypeFixtures::SECOND_ORGANIZATION_TYPE_REFERENCE));

        $manager->persist($firstOrganizationOrganizationType);
        $manager->persist($secondOrganizationOrganizationType);

        $manager->flush();
    }
}
