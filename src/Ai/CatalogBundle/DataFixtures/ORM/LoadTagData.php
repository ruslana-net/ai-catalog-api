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
use Ai\CatalogBundle\Entity\Tag;

/**
 * Class LoadTagData User Data Fixtures
 *
 * @package Ai\CatalogBundle\DataFixtures\ORM
 */
class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    const TAGS = ['red', 'green', 'red'];

    /**
     * Load tags fixtures
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::TAGS as $k => $tagName) {
            $tag = (new Tag($tagName))->setEnabled(true);

            $manager->persist($tag);
            $this->addReference("tag_$k", $tag);
        }

        $manager->flush();
    }

    /**
     * Get fixtures order
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}