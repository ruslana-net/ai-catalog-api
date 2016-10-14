<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Form
 * @package  Ai\CatalogBundle\Form\Type
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagType
 *
 * @package Ai\CatalogBundle\Form
 */
class TagType extends AbstractType
{
    /**
     * Form building
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('enabled')
        ;
    }
    
    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ai\CatalogBundle\Entity\Tag',
            'csrf_protection'   => false
        ));
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getName()
    {
        return 'ai_catalog_tag';
    }
}
