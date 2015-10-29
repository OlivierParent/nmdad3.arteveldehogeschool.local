<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\ArticleType;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArticleController.
 *
 * @Route("/v1")
 */
class ArticleController extends FOSRestController
{
    /**
     * Test API options and requirements.
     *
     * @FOSRest\Options("/users/{user_id}/articles/")
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK: "OK",
     *     }
     * )
     */
    public function optionsAction()
    {
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');

        return $response;
    }

    /**
     * Returns all articles.
     *
     * @param $user_id
     * @param ParamFetcher $paramFetcher
     *
     * @return mixed
     * @FOSRest\Get("/users/{user_id}/articles.{_format}", requirements = { "_format": "json|jsonp|xml" }, defaults = {"_format": "json"})
     * @FOSRest\QueryParam(name="sort", requirements="id|title", default="id", description="Order by Article id or Article title.")
     * @FOSRest\QueryParam(name="order", requirements="asc|desc", default="asc", description="Order result ascending or descending.")
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK: "OK",
     *     }
     * )
     */
    public function getAllAction($user_id, ParamFetcher $paramFetcher)
    {
        # GET http://www.nmdad3.arteveldehogeschool.local/app_dev.php/api/v1/articles.json
        # GET http://www.nmdad3.arteveldehogeschool.local/app_dev.php/api/v1/articles.xml?sort=title&amp;order=desc

//        dump([
//            $paramFetcher->get('sort'),
//            $paramFetcher->get('order'),
//            $paramFetcher->all(),
//        ]);

        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository('AppBundle:User')
            ->find($user_id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('Not found');
        }

        $posts = $user->getPosts();

        $articles = $posts->filter(
            function($post) {
                return $post instanceof Article;
            });

        return $articles;
    }

    /**
     * Returns an article.
     *
     * @param $user_id
     * @param $article_id
     *
     * @return object
     *
     * @FOSRest\Get("/users/{user_id}/articles/{article_id}.{_format}", requirements = {"article_id": "\d+", "_format": "json|xml" }, defaults = {"_format": "json"})
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK        : "OK",
     *         Response::HTTP_NO_CONTENT: "No Content",
     *         Response::HTTP_NOT_FOUND : "Not Found"
     *     }
     * )
     */
    public function getArticleAction($user_id, $article_id)
    {
        # GET http://www.nmdad3.arteveldehogeschool.local/api/v1/users/1/articles/1.json

        $em = $this->getDoctrine()->getManager();

        $article = $em
            ->getRepository('AppBundle:Article')
            ->find($article_id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException('Not found');
        }

        if ($article->getUser()->getId() === (int) $user_id) {
            return $article;
        }
    }

    /**
     * Post a new article.
     *
     * { "article": { "title": "Lorem", "body": "ipsum" } }
     *
     * @param Request $request
     * @param $user_id
     *
     * @return View|Response
     *
     * @FOSRest\View()
     * @FOSRest\Post("/users/{user_id}/articles/", requirements = {"user_id": "\d+"})
     * @Nelmio\ApiDoc(
     *     input = "Artevelde\ApiBundle\Form\ArticleType",
     *     statusCodes = {
     *         Response::HTTP_CREATED: "Created"
     *     }
     * )
     */
    public function postAction(Request $request, $user_id)
    {
        # POST http://www.nmdad3.arteveldehogeschool.local/app_dev.php/api/v1/user/1/articles/

        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('AppBundle:User')
            ->find($user_id);
        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $article = new Article();
        $article->setUser($user);

        return $this->processArticleForm($request, $article);
    }

    /**
     * Update an article.
     *
     * @param Request $request
     * @param $user_id
     * @param $article_id
     *
     * @return Response
     *
     * @FOSRest\View
     * @FOSRest\Put("/users/{user_id}/articles/{article_id}.{_format}", requirements={"user_id": "\d+", "article_id": "\d+", "_format": "json|xml"}, defaults = {"_format": "json"})
     * @Nelmio\ApiDoc(
     *     input = "Artevelde\ApiBundle\Form\ArticleType",
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putAction(Request $request, $user_id, $article_id)
    {
        # E.g. PUT http://www.nmdad3.arteveldehogeschool.local/app_dev.php/api/v1/users/1/articles/1

        $em = $this->getDoctrine()->getManager();
        $article = $em
            ->getRepository('AppBundle:Article')
            ->find($article_id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException();
        }

        if ($article->getUser()->getId() === (int) $user_id) {
            return $this->processArticleForm($request, $article);
        }
    }

    /**
     * Delete an article.
     *
     * @param $user_id
     * @param $article_id
     *
     * @throws NotFoundHttpException
     * @FOSRest\View(statusCode=204)
     * @FOSRest\Delete("/users/{user_id}/articles/{article_id}.{_format}", requirements = {"user_id": "\d+", "article_id": "\d+", "_format": "json|xml" }, defaults = {"_format": "json"})
     * @Nelmio\ApiDoc(
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content",
     *         Response::HTTP_NOT_FOUND : "Not Found"
     *     }
     * )
     */
    public function deleteAction($user_id, $article_id)
    {
        # E.g. DELETE http://www.nmdad3.arteveldehogeschool.local/app_dev.php/api/v1/users/1/articles/1

        $em = $this->getDoctrine()->getManager();

        $article = $em
            ->getRepository('AppBundle:Article')
            ->find($article_id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException();
        }

        if ($article->getUser()->getId() === (int) $user_id) {
            $em->remove($article);
            $em->flush();
        }
    }

    // Convenience methods
    // -------------------

    /**
     * Process ArticleType Form.
     *
     * @param Request $request
     * @param Article $article
     *
     * @return View|Response
     */
    private function processArticleForm(Request $request, Article $article)
    {
        $form = $this->createForm(new ArticleType(), $article, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($article->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;

            $em = $this->getDoctrine()->getManager();
            $em->persist($article); // Manage entity Article for persistence.
            $em->flush();           // Persist to database.

            $response = new Response();
            $response->setStatusCode($statusCode);

            // Redirect to URI of resource.
            $response->headers->set('Location',
                $this->generateUrl('api_article_getarticle', [
                    'user_id' => $article->getUser()->getId(),
                    'article_id' => $article->getId(),
                ], /* absolute path = */true)
            );

            return $response;
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
