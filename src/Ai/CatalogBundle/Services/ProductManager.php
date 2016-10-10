<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Manager
 * @package  Ai\CatalogBundle\Services
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductManager
 * 
 * @package Ai\CatalogBundle\Services
 */
class ProductManager
{
    protected $em;

    /**
     * ProductManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        $this->em->getRepository('Product')->findAll();
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $this->em->getRepository('Product')->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $this->em->getRepository('Product')->findOneBy($criteria, $orderBy);
    }


    public function create(Request $request)
    {

    }

    public function update(Product $product, Request $request)
    {

    }

    public function delete(Product $product)
    {

    }
}