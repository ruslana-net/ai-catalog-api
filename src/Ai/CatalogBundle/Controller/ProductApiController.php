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
     * @ApiDoc(
     *     description="Gets all products",
     *     filters={
     *         {"name"="fields", "dataType"="string", "pattern"="id,name,descr,category,tags"},
     *         {"name"="tags",   "dataType"="string", "pattern"="news,articles"},
     *         {"name"="search", "dataType"="string"},
     *         {"name"="page",   "dataType"="integer"},
     *         {"name"="limit",  "dataType"="integer"}
     *     },
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/product", name="catalog_rest_products_getall", defaults={"_format" = "json"})
     * @View
     *
     * @param Request $request
     *
     * @return array
     */
    public function getAllAction(Request $request)
    {
        return $this->get("ai_catalog.product_manager")->findAll(
            $request->query->get("fields"),
            $request->query->get("tags"),
            $request->query->get('search'),
            $request->query->get("page"),
            $request->query->get("limit")
        );
    }

    /**
     * @ApiDoc(
     *     description="Gets a product",
     *     filters={
     *         {"name"="fields", "dataType"="string", "pattern"="id,name,descr"}
     *     },
     *     statusCodes={
     *         404="When the product does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/product/{id}", name="catalog_rest_product_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     *
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function getAction(int $id, Request $request)
    {
        return $this->get("ai_catalog.product_manager")
            ->find(
                $id,
                $request->query->get("fields")
            );
    }
    
    /**
     * @ApiDoc(
     *     description="Creates a product for the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The product name"},
     *         {"name"="descr", "dataType"="textarea", "required"=false, "description"="The product description"},
     *         {"name"="price", "dataType"="string",  "required"=false, "description"="The product price"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=false, "description"="The product enabled"},
     *         {"name"="ceoTitle", "dataType"="textarea", "required"=false, "description"="The product ceo title"},
     *         {"name"="ceoDescription", "dataType"="textarea", "required"=false, "description"="The product ceo description"},
     *         {"name"="ceoKeywords", "dataType"="string", "required"=false, "description"="The product ceo keywords"},
     *         {"name"="category", "dataType"="string", "required"=false, "description"="The product category"},
     *         {"name"="tags[]", "dataType"="string", "required"=false, "description"="The product tags"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the product",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/product", name="catalog_rest_product_create", defaults={"_format" = "json"})
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
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
     * @ApiDoc(
     *     description="Updates a product of the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The product name"},
     *         {"name"="descr", "dataType"="textarea", "required"=false, "description"="The product description"},
     *         {"name"="price", "dataType"="string",  "required"=false, "description"="The product price"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=false, "description"="The product enabled"},
     *         {"name"="ceoTitle", "dataType"="textarea", "required"=false, "description"="The product ceo title"},
     *         {"name"="ceoDescription", "dataType"="textarea", "required"=false, "description"="The product ceo description"},
     *         {"name"="ceoKeywords", "dataType"="textarea", "required"=false, "description"="The product ceo keywords"},
     *         {"name"="category", "dataType"="string", "required"=false, "description"="The product category"},
     *         {"name"="tags[]", "dataType"="string", "required"=false, "description"="The product tags"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the product",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/product/{id}", name="catalog_rest_product_update", defaults={"_format" = "json"})
     *
     * @param Request $request
     * @param Product $product
     *
     * @return \FOS\RestBundle\View\View
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
     *
     * @param Product $product
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
}
