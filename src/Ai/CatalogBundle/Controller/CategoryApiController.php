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


use Ai\CatalogBundle\Entity\Category;
use Ai\CatalogBundle\Form\Type\CategoryType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

class CategoryApiController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     description="Gets all categories",
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/category", name="catalog_rest_categories_getall", defaults={"_format" = "json"})
     * @View
     *
     * @return View
     */
    public function getAllAction()
    {
        return $this
            ->getDoctrine()
            ->getRepository('AiCatalogBundle:Category')
            ->findAll();
    }

    /**
     * @ApiDoc(
     *     description="Gets a category",
     *     statusCodes={
     *         404="When the category does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/category/{id}", name="catalog_rest_category_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     *
     * @param Category $category
     *
     * @return View
     */
    public function getAction(Category $category)
    {
        return $category;
    }
    
    /**
     * @ApiDoc(
     *     description="Creates a category for the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The category name"},
     *         {"name"="descr", "dataType"="textarea",  "required"=true,  "description"="The category description"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=true,  "description"="The category enabled"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the category",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/category", name="catalog_rest_category_create", defaults={"_format" = "json"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        $category = new Category();

        return $this->processForm($category, $request);
    }

    /**
     * @ApiDoc(
     *     description="Updates a category of the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The category name"},
     *         {"name"="descr", "dataType"="textarea",  "required"=true,  "description"="The category description"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=true,  "description"="The category enabled"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the category",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/category/{id}", name="catalog_rest_category_update", defaults={"_format" = "json"})
     *
     * @param Category $category
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function updateAction(Category $category, Request $request)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        return $this->processForm($category, $request);
    }

    /**
     * @ApiDoc(
     *     description="Deletes a category of the authenticated user",
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the category",
     *         404="When the category does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/category/{id}", name="catalog_rest_category_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     *
     * @param Category $category
     */
    public function deleteAction(Category $category)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($category);
        $em->flush();
    }



    /**
     * Create or update category by request data
     *
     * @param Category $category
     * @param Request $request
     *
     * @return View
     */
    private function processForm(Category $category, Request $request)
    {
        $isNew = null === $category->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $method = $isNew ? 'POST' : 'PUT';
        $form = $this->get('form.factory')->createNamed(
            '',
            CategoryType::class,
            $category,
            ['method' => $method]
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $category = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($category);
            $em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->get('router')->generate(
                    'catalog_rest_category_get',
                    array('id' => $category->getId()),
                    true
                );
            }

            return \FOS\RestBundle\View\View::create($category, $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }
}