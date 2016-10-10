<?php

namespace Ai\CatalogBundle\DataFixtures\ORM;

use Ai\CatalogBundle\Entity\Category;
use Ai\CatalogBundle\Entity\Product;
use Ai\CatalogBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    const DATA = ['First', 'Second', 'Third'];

    public function load(ObjectManager $manager)
    {
        foreach(self::DATA as $key){
            $tag = new Tag("$key tag");
            $tag->setEnabled(true);

            $category = new Category("$key category");
            $category->setDescr("$key category description");
            $category->setEnabled(true);

            $product = new Product();
            $product->setName("$key product");
            $product->setDescr("$key product description");
            $product->setPrice(242.00);
            $product->setCategory($category);
            $product->addTag($tag);
            $product->setUser($this->getReference('user'));
            $product->setEnabled(true);
            $manager->persist($tag);
            $manager->persist($category);
            $manager->persist($product);
            $manager->flush();
        }
    }

    public function getOrder()
    {
        return 2;
    }
}