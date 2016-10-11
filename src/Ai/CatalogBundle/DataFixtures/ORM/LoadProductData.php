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

use Ai\CatalogBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadProductData Product Data Fixtures
 *
 * @package Ai\CatalogBundle\DataFixtures\ORM
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    const DATA = ['Beaf', 'Table', 'iPhone'];

    /**
     * Load products fixtures
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $k => $productName) {
            $product = new Product();
            $product->setName($productName);
            $product->setDescr("$productName description");
            $product->setPrice(242.00);
            $product->setCategory($this->getReference("category_$k"));
            $product->addTag($this->getReference("tag_$k"));
            $product->setUser($this->getReference('user'));
            $product->setEnabled(true);
            $manager->persist($product);
            $manager->flush();

            $this->addReference("product_$k", $product);
        }
    }

    /**
     * Get fixtures order
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}