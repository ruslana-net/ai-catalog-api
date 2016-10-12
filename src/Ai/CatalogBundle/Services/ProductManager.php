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
use Ai\CatalogBundle\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ProductManager
 *
 * @package Ai\CatalogBundle\Services
 */
class ProductManager
{
    /**
     * Items number on the page
     *
     * var int
     */
    const numItemsPerPage = 10;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * ProductManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    )
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * Get all filtered products
     *
     * @param string $fieldsFilter selected fields id,name,descr,category,tags,etc
     * @param string $tagsFilter search by tags name news,anothe tag,etc
     * @param string $searchString search by product name and description
     * @param int $page page num
     * @param int $limit num per page
     *
     * @return array products array and pagination data
     */
    public function findAll(
        string $fieldsFilter = null,
        string $tagsFilter = null,
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

    /**
     * @param Product $product
     */
    public function delete(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    /**
     * Create product
     *
     * @param Request $request
     * @param User $user
     *
     * @return View
     */
    public function create(Request $request, User $user) : View
    {
        $product = new Product();
        $product->setUser($user);

        return $this->processForm($product, $request);
    }

    /**
     * Update product
     *
     * @param Product $product
     * @param Request $request
     * @return View
     */
    public function update(Product $product, Request $request) : View
    {
        return $this->processForm($product, $request);
    }

    /**
     * Create or update product by request data
     *
     * @param Product $product
     * @param Request $request
     *
     * @return View
     */
    private function processForm(Product $product, Request $request) : View
    {
        $isNew = null === $product->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $form = $this->formFactory->createNamed('', ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $product = $form->getData();
            $this->em->persist($product);
            $this->em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->router->generate(
                    'catalog_rest_product_get',
                    array('id' => $product->getId()),
                    true
                );
            }

            return \FOS\RestBundle\View\View::create($product->setUser(null), $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form, Response::HTTP_BAD_REQUEST);
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