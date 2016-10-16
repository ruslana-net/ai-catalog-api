<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Tests
 * @package  Tests\AiCatalogBundle\Form\Type
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Tests\AiCatalogBundle\Form\Type;

use Ai\CatalogBundle\Entity\Category;
use Ai\CatalogBundle\Entity\Product;
use Ai\CatalogBundle\Entity\Tag;
use Ai\CatalogBundle\Form\Type\ProductType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ProductTypeTest
 * 
 * @package Tests\AiCatalogBundle\Form\Type
 */
class ProductTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $form = $this->factory->create(ProductType::class);
        $tag = $this->createMock(Tag::class);
        $category = $this->createMock(Category::class);

        $formData = array(
            'name' => 'Test',
            'descr' => 'Test',
            'price' => 200.40,
            'ceoTitle' => 'Test',
            'ceoDescription' => 'Test',
            'ceoKeywords' => 'Test',
            'enabled' => true,
            'tags' => new ArrayCollection([$tag]),
            'category' => $category,
        );

        $object = new Product();
        $object->setName('Test');
        $object->setDescr('Test');
        $object->setPrice(200.40);
        $object->setCeoTitle('Test');
        $object->setCeoDescription('Test');
        $object->setCeoKeywords('Test');
        $object->setEnabled(true);
        $object->addTag($tag);
        $object->setCategory($category);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}