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
use Ai\CatalogBundle\Form\Type\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CategoryTypeTest
 * 
 * @package Tests\AiCatalogBundle\Form\Type
 */
class CategoryTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'Test',
            'descr' => 'Descr',
            'enabled' => true,
        );

        $form = $this->factory->create(CategoryType::class);

        $object = new Category();
        $object->setName('Test');
        $object->setDescr('Descr');
        $object->setEnabled(true);

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