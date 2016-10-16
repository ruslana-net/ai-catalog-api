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


use Ai\CatalogBundle\Entity\Tag;
use Ai\CatalogBundle\Form\Type\TagType;
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

class TagApiController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     description="Gets all tags",
     *     statusCodes={
     *         200="When successful"
     *     }
     * )
     * @Get("/tag", name="catalog_rest_tags_getall", defaults={"_format" = "json"})
     * @View
     *
     * @return View
     */
    public function getAllAction()
    {
        return $this
            ->getDoctrine()
            ->getRepository('AiCatalogBundle:Tag')
            ->findAll();
    }

    /**
     * @ApiDoc(
     *     description="Gets a tag",
     *     statusCodes={
     *         404="When the tag does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/tag/{id}", name="catalog_rest_tag_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     *
     * @param Tag $tag
     *
     * @return View
     */
    public function getAction(Tag $tag)
    {
        return $tag;
    }
    
    /**
     * @ApiDoc(
     *     description="Creates a tag for the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The tag name"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=true,  "description"="The tag enabled"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the tag",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/tag", name="catalog_rest_tag_create", defaults={"_format" = "json"})
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

        $tag = new Tag();

        return $this->processForm($tag, $request);
    }

    /**
     * @ApiDoc(
     *     description="Updates a tag of the authenticated user",
     *     parameters={
     *         {"name"="name", "dataType"="string",  "required"=true,  "description"="The tag name"},
     *         {"name"="enabled", "dataType"="boolean",  "required"=true,  "description"="The tag enabled"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the tag",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/tag/{id}", name="catalog_rest_tag_update", defaults={"_format" = "json"})
     *
     * @param Tag $tag
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function updateAction(Tag $tag, Request $request)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        return $this->processForm($tag, $request);
    }

    /**
     * @ApiDoc(
     *     description="Deletes a tag of the authenticated user",
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the tag",
     *         404="When the tag does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/tag/{id}", name="catalog_rest_tag_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     *
     * @param Tag $tag
     */
    public function deleteAction(Tag $tag)
    {
        /** @var \Ai\CatalogBundle\Entity\User $user */
        if (!$user = $this->get("security.token_storage")->getToken()->getUser()) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($tag);
        $em->flush();
    }



    /**
     * Create or update tag by request data
     *
     * @param Tag $tag
     * @param Request $request
     *
     * @return View
     */
    private function processForm(Tag $tag, Request $request)
    {
        $isNew = null === $tag->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $method = $isNew ? 'POST' : 'PUT';
        $form = $this->get('form.factory')->createNamed(
            '',
            TagType::class,
            $tag,
            ['method' => $method]
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tag = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($tag);
            $em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->get('router')->generate(
                    'catalog_rest_tag_get',
                    array('id' => $tag->getId()),
                    true
                );
            }

            return \FOS\RestBundle\View\View::create($tag, $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }
}