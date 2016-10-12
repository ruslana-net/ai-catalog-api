<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category DataFixtures
 * @package  Ai\CatalogBundle\DataFixtures\ORM
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ai\CatalogBundle\Entity\User;

/**
 * Class LoadUserData User Data Fixtures
 *
 * @package Ai\CatalogBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load users fixtures
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@test.com');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }

    /**
     * Get fixtures order
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}