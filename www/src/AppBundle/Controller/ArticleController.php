<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArticleController extends Controller
{
    /**
     * @Route("/articles")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        dump($articles); // Dump to the Symfony Development Toolbar.

        // Send variables to the view.
        return [
            'articles' => $articles,
        ];
    }
}
