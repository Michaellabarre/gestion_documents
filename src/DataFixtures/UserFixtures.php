<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setEmail('email'.$i.'@test.com');
            $user->setPlainPassword('user'.$i);
            $user->setEnabled(true);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
