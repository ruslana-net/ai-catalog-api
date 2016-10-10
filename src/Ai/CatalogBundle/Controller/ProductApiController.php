<?php
/**
 * AiCatalogBundle
 *
 * PHP Version 7
 *
 * @category Controller
 * @package  Ai\CatalogBundle\Controller
 * @author   Ruslan Muriev <ruslana.net@gmail.com>
 * @license  https://github.com/ruslana-net/ai-catalog-api/LICENSE MIT License
 * @link     https://github.com/ruslana-net/ai-catalog-api
 */

namespace Ai\CatalogBundle\Controller;

use Ai\CatalogBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

/**
 * Class ProductApiController
 * 
 * @package Ai\CatalogBundle\Controller
 */
class ProductApiController extends Controller
{
    protected $securityContext;

    protected $pm;

    public function __construct()
    {
        $this->securityContext = $this->get("security.context");
        $this->pm = $this->get("ai_catalog.product_manager");
    }

    /**
     * @param Request $request
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *     description="Creates a product for the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The product name"},
     *         {"name"="descr", "dataType"="string", "required"=false, "description"="The product description"}
     *         {"name"="price", "dataType"="float",  "required"=false, "description"="The product price"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the product",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/product", name="catalog_rest_product_create", defaults={"_format" = "json"})
     */
    public function createAction(Request $request)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->securityContext->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        return $this->pm->create($request);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *     description="Updates a product of the authenticated user",
     *     parameters={
     *         {"name"="title", "dataType"="string", "required"=true, "description"="The product title"},
     *         {"name"="descr", "dataType"="string", "required"=true, "description"="The product description"}
     *         {"name"="price", "dataType"="float",  "required"=true, "description"="The product price"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the product",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/product/{id}", name="catalog_rest_product_update", defaults={"_format" = "json"})
     */
    public function updateAction(Request $request, Product $product)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->securityContext->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        //If product dosn't create current user throw exceptions
        if ($product->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        return $this->pm->update($product, $request);
    }

    /**
     * @ApiDoc(
     *     description="Deletes a product of the authenticated user",
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the product",
     *         404="When the product does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/product/{id}", name="catalog_rest_product_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     */
    public function deleteAction(Product $product)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->securityContext->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        //If product dosn't create current user throw exceptions
        if ($product->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        return $this->pm->delete($product);
    }

    public function getAllAction()
    {

    }

    public function getAction()
    {

    }
}
