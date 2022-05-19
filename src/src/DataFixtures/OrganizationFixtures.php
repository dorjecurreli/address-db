<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public const FIRST_ORGANIZATION_REFERENCE = 'first-organization';
    public const SECOND_ORGANIZATION_REFERENCE = 'second-organization';


    public function load(ObjectManager $manager): void
    {
        $firstOrganization = new Organization();
        $firstOrganization->setName('OrganizationGermany');
        $firstOrganization->setAddress($this->getReference(AddressFixtures::FIRST_ADDRESS_REFERENCE));
        $firstOrganization->setContactDetail($this->getReference(ContactDetailFixtures::FIRST_CONTACT_DETAIL_REFERENCE));

        $this->addReference(self::FIRST_ORGANIZATION_REFERENCE, $firstOrganization);


        $secondOrganization = new Organization();
        $secondOrganization->setName('OrganizationItaly');
        $secondOrganization->setAddress($this->getReference(AddressFixtures::SECOND_ADDRESS_REFERENCE));
        $secondOrganization->setContactDetail($this->getReference(ContactDetailFixtures::SECOND_CONTACT_DETAIL_REFERENCE));
        $this->addReference(self::SECOND_ORGANIZATION_REFERENCE, $secondOrganization);



        $manager->persist($firstOrganization);
        $manager->persist($secondOrganization);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            AddressFixtures::class,
            PersonFixtures::class,
            PersonLabelFixtures::class,
            OrganizationOrganizationTypeFixtures::class,
            LabelFixtures::class,
            OrganizationTypeFixtures::class,
            ContactDetailFixtures::class

        ];
    }
}
