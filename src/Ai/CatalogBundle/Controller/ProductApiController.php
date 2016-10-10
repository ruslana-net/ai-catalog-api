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
use Ai\CatalogBundle\Services\ProductManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
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
class ProductApiController extends FOSRestController
{
    /**
     * @param Request $request
     * @throws AccessDeniedException
     *
     * @ApiDoc(
     *     description="Creates a product for the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The product name"},
     *         {"name"="descr", "dataType"="string", "required"=false, "description"="The product description"},
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
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        return $this->get("ai_catalog.product_manager")->create($request, $user);
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
     *         {"name"="descr", "dataType"="string", "required"=true, "description"="The product description"},
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
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        //If product dosn't create current user throw exceptions
        if ($product->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        return $this->get("ai_catalog.product_manager")->update($product, $request);
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
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        //If product dosn't create current user throw exceptions
        if ($product->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        return $this->get("ai_catalog.product_manager")->delete($product);
    }

    /**
     * @ApiDoc(
     *     description="Gets all products",
     *     filters={
     *         {"name"="fields", "dataType"="string", "pattern"="id,name,descr"},
     *         {"name"="tags", "dataType"="string", "pattern"="news,articles"},
     *         {"name"="search", "dataType"="string"},
     *         {"name"="page", "dataType"="integer"},
     *         {"name"="limit", "dataType"="integer"}
     *     },
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/product", name="catalog_rest_products_getall", defaults={"_format" = "json"})
     * @View
     */
    public function getAllAction(Request $request)
    {
        $fields = $request->query->get("fields")
            ? explode(',', $request->query->get("fields"))
            : null;

        $tags = $request->query->get("tags")
            ? explode(',', $request->query->get("tags"))
            : null;

        return $this->get("ai_catalog.product_manager")->findAll(
            $fields,
            $tags,
            $request->query->get('search'),
            $request->query->get("page"),
            $request->query->get("limit")
        );
    }

    /**
     * @param Product $product
     *
     * @ApiDoc(
     *     description="Gets a product",
     *     statusCodes={
     *         404="When the product does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/product/{id}", name="catalog_rest_product_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     */
    public function getAction(Product $product)
    {
        return $product;
    }
}
