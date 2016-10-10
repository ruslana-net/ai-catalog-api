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

use Ai\CatalogBundle\Entity\Product;
use Ai\CatalogBundle\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class ProductManager
 *
 * @package Ai\CatalogBundle\Services
 */
class ProductManager
{
    const numItemsPerPage = 10;

    protected $em;

    protected $paginator;

    /**
     * ProductManager constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @param array  $fields selected fields array
     * @param array  $tags   search by tags name
     * @param string $search search by product name and description
     * @param int    $page   page num
     * @param int    $limit  num per page
     *
     * @return array products array and pagination data
     */
    public function findAll(
        array  $fields = null,
        array  $tags   = null,
        string $search = null,
        int    $page   = null,
        int    $limit  = null
    ) : array
    {
        $query = $this->em
            ->getRepository('AiCatalogBundle:Product')
            ->findBySearchQuery(
                $fields,
                $tags,
                $search
            );

        /** @var SlidingPagination $pagination */
        $pagination = $this->paginator->paginate(
            $query,
            intval($page ?? 1),
            intval($limit ?? self::numItemsPerPage)
        );

        return [
            'items' => $pagination->getItems(),
            'paginationData' => $pagination->getPaginationData(),
        ];
    }


    public function create(array $data, User $user) : bool
    {
        $data = [
            'name' => 'New product',
            'descr' => 'New product descr',
            'price' => 245.04,
            'category' => 'New Category',
            'tags' => ['article', 'news', 'economic', 'politic']
        ];


    }

    public function update(Product $product, array $data) : bool
    {

    }

    /**
     * @param Product $product
     */
    public function delete(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}