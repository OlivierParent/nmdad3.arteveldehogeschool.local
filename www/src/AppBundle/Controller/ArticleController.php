<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\Article as ArticleForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController.
 *
 * @Route("/posts-articles")
 */
class ArticleController extends Controller
{
    /**
     * Lists all Article entities.
     *
     * @Route("/", name="articles")
     * @Method("GET")
     * @Template("Article/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

//        dump($articles); // Dump to the Symfony Development Toolbar.

        // Send variables to the view.
        return [
            'articles' => $articles,
        ];
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/", name="articles_create")
     * @Method("POST")
     * @Template("Article/new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $article->setUser($this->getUser());
        $form = $this->createCreateForm($article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return [
            'article' => $article,
            'new_form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $article The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $article)
    {
        $formType = new ArticleForm\NewType();
        $form = $this->createForm($formType, $article, [
            'action' => $this->generateUrl('articles_create'),
            'method' => Request::METHOD_POST,
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     * @Route("/new", name="articles_new")
     * @Method("GET")
     * @Template("Article/new.html.twig")
     */
    public function newAction()
    {
        $article = new Article();
        $newForm = $this->createCreateForm($article);

        return [
            'new_form' => $newForm->createView(),
        ];
    }
}
