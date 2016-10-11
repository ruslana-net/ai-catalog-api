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
use Ai\CatalogBundle\Entity\Category;

/**
 * Class LoadCategoryData User Data Fixtures
 *
 * @package Ai\CatalogBundle\DataFixtures\ORM
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    const CATEGORIES = ['food', 'furniture', 'phones'];

    /**
     * Load categories fixtures
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $k => $categoryName) {
            $category = (new Category($categoryName))
                ->setDescr($categoryName . ' description')
                ->setEnabled(true);

            $manager->persist($category);
            $this->addReference("category_$k", $category);
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
        return 3;
    }
}