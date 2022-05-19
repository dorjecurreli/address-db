<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setEmail('admin@dev.dev');
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword('$2y$13$CVNF.5BR2txNDAZyxxbxTunAFh/o2SH9r1oXeq0R5c7UGSy5IXJ7i');

        $adminUser->setIsActivated(1);
        $adminUser->setAddress($this->getReference(AddressFixtures::FIRST_ADDRESS_REFERENCE));

        $user = new User();
        $user->setEmail('user@dev.dev');
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword('$2y$13$CVNF.5BR2txNDAZyxxbxTunAFh/o2SH9r1oXeq0R5c7UGSy5IXJ7i');
        $user->setIsActivated(1);
        $user->setAddress($this->getReference(AddressFixtures::FIRST_ADDRESS_REFERENCE));


        $allUsers = array($adminUser, $user);

        foreach ($allUsers as $user) {
            $manager->persist($user);
        }


        $manager->flush();
    }
}
