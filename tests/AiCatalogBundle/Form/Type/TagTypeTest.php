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

use Ai\CatalogBundle\Entity\Tag;
use Ai\CatalogBundle\Form\Type\TagType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class TagTypeTest
 * 
 * @package Tests\AiCatalogBundle\Form\Type
 */
class TagTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'Test',
            'enabled' => true,
        );

        $form = $this->factory->create(TagType::class);

        $object = new Tag();
        $object->setName('Test');
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