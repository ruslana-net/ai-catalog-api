<?php

namespace Ai\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AiCatalogBundle:Default:index.html.twig');
    }
}
