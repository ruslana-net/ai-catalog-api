<?php

namespace Ai\CatalogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ai\CatalogBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@test.com');
        $user->setPassword('123456');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}