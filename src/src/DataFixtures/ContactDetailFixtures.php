<?php

namespace App\DataFixtures;

use App\Entity\ContactDetail;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactDetailFixtures extends Fixture
{
    public const FIRST_CONTACT_DETAIL_REFERENCE = 'first-contact-detail';
    public const SECOND_CONTACT_DETAIL_REFERENCE = 'second-contact-detail';

    public function load(ObjectManager $manager): void
    {
        $firstContantDetail = new ContactDetail();
        $firstContantDetail->setEmail('curreli@edu.helliwood.de');
        $firstContantDetail->setFax('123456789');
        $firstContantDetail->setTelephoneNumber('11112222233333444');
        $firstContantDetail->setInternetSite('www.helliwood.de');
        $firstContantDetail->setModifiedAt(new DateTimeImmutable);
        $this->addReference(self::FIRST_CONTACT_DETAIL_REFERENCE, $firstContantDetail);

        $secondContantDetail = new ContactDetail();
        $secondContantDetail->setEmail('test@helliwood.de');
        $secondContantDetail->setFax('666666666666');
        $secondContantDetail->setTelephoneNumber('8888888877777766666');
        $secondContantDetail->setInternetSite('www.pippo.com');
        $secondContantDetail->setModifiedAt(new DateTimeImmutable);
        $this->addReference(self::SECOND_CONTACT_DETAIL_REFERENCE, $secondContantDetail);

        $manager->persist($firstContantDetail);
        $manager->persist($secondContantDetail);
        $manager->flush();

    }
}
