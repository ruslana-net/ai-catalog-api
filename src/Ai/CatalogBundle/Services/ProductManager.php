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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Get all filtered products
     *
     * @param string $fieldsFilter selected fields id,name,descr,category,tags,etc
     * @param string $tagsFilter   search by tags name news,anothe tag,etc
     * @param string $searchString search by product name and description
     * @param int $page page num
     * @param int $limit num per page
     *
     * @return array products array and pagination data
     */
    public function findAll(
        string $fieldsFilter = null,
        string $tagsFilter   = null,
        string $searchString = null,
        int $page = null,
        int $limit = null
    ) : array
    {
        $products = self::productFieldsFilter(
            $this->em
                ->getRepository('AiCatalogBundle:Product')
                ->findByFilters(
                    $tagsFilter,
                    $searchString
                )
            , $fieldsFilter);

        /** @var SlidingPagination $pagination */
        $pagination = $this->paginator->paginate(
            $products,
            intval($page ?? 1),
            intval($limit ?? self::numItemsPerPage)
        );

        return [
            'items' => $pagination->getItems(),
            'paginationData' => $pagination->getPaginationData(),
        ];
    }

    /**
     * Get one product by id
     * 
     * @param $id
     * @param string|null $fields
     * @return mixed
     */
    public function find($id, string $fields = null)
    {
        return self::productFieldsFilter(
            $this->em
                ->getRepository('AiCatalogBundle:Product')
                ->findOneById($id)
            , $fields);
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

    /**
     * Filter products fields
     *
     * @param array $products
     * @param string|null $fields
     *
     * @return array
     */
    protected static function productFieldsFilter(array $products, string $fields = null) : array
    {
        if ($fields === null || empty($products))
            return $products;

        $fields = explode(',', str_replace(' ', '', $fields));

        return self::array_filter_recursive($products,
            function ($key) use ($fields) {
                if (is_int($key)) {
                    return true;
                } else {
                    return in_array($key, $fields);
                }
            }
            , ARRAY_FILTER_USE_KEY);
    }

    /**
     * Recursively filter an array
     *
     * @param array $array
     * @param callable $callback
     * @param int $flag [optional] <p>
     * Flag determining what arguments are sent to <i>callback</i>:
     * </p><ul>
     * <li>
     * <b>ARRAY_FILTER_USE_KEY</b> - pass key as the only argument
     * to <i>callback</i> instead of the value</span>
     * </li>
     * <li>
     * <b>ARRAY_FILTER_USE_BOTH</b> - pass both value and key as
     * arguments to <i>callback</i> instead of the value</span>
     * </li>
     * </ul>
     *
     * @return array
     */
    protected static function array_filter_recursive(array $array, callable $callback = null, $flag = 0) : array
    {
        $array = is_callable($callback) ? array_filter($array, $callback, $flag) : array_filter($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::array_filter_recursive($value, $callback, $flag);
            }
        }

        return $array;
    }
}