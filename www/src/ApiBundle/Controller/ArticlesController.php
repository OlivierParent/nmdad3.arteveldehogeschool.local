<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\ArticleType;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArticlesController.
 */
class ArticlesController extends Controller
{
    /**
     * Test API options and requirements.
     *
     * @return Response
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK: "OK"
     *     }
     * )
     */
    public function optionsArticlesAction()
    {
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');

        return $response;
    }

    /**
     * Returns all articles.
     *
     * @param ParamFetcher $paramFetcher
     * @param $user_id
     *
     * @return mixed
     *
     * @FOSRest\View()
     * @FOSRest\Get(
     *     requirements = {
     *         "_format" : "json|jsonp|xml"
     *     }
     * )
     * @FOSRest\QueryParam(
     *     name = "sort",
     *     requirements = "id|title",
     *     default = "id",
     *     description = "Order by Article id or Article title."
     * )
     * @FOSRest\QueryParam(
     *     name = "order",
     *     requirements = "asc|desc",
     *     default = "asc",
     *     description = "Order result ascending or descending."
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK : "OK"
     *     }
     * )
     */
    public function getArticlesAction(ParamFetcher $paramFetcher, $user_id)
    {
        # HTTP method: GET
        # Host/port  : http://www.nmdad3.arteveldehogeschool.local
        #
        # Path       : /app_dev.php/api/v1/users/1/articles.json
        # Path       : /app_dev.php/api/v1/users/1/articles.xml
        # Path       : /app_dev.php/api/v1/users/1/articles.xml?sort=title&amp;order=desc

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

        $articles = $posts
            ->filter(
                function ($post) {
                    return $post instanceof Article;
                }
            )->getValues();

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
     * @FOSRest\Get(
     *     requirements = {
     *         "user_id"   : "\d+",
     *         "article_id": "\d+",
     *         "_format"   : "json|xml"
     *     }
     * )
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
        # HTTP method: GET
        # Host/port  : http://www.nmdad3.arteveldehogeschool.local
        #
        # Path       : /app_dev.php/api/v1/users/1/articles/1.json

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
     * @FOSRest\Post(
     *     "/users/{user_id}/articles/",
     *     requirements = {
     *         "user_id": "\d+"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     input = ArticleType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postArticleAction(Request $request, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('AppBundle:User')
            ->find($user_id);
        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $article = new Article();
        $article->setUser($user);

        $logger = $this->get('logger');
        $logger->info($request);

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
     * @FOSRest\View()
     * @FOSRest\Put(
     *     requirements = {
     *         "user_id" : "\d+",
     *         "article_id" : "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     input = ArticleType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putArticleAction(Request $request, $user_id, $article_id)
    {
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
     * @FOSRest\View(statusCode = 204)
     * @FOSRest\Delete(
     *     requirements = {
     *         "user_id"   : "\d+",
     *         "article_id": "\d+",
     *         "_format"   : "json|xml"
     *     },
     *     defaults = {"_format": "json"}
     * )
     * @Nelmio\ApiDoc(
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content",
     *         Response::HTTP_NOT_FOUND : "Not Found"
     *     }
     * )
     */
    public function deleteArticleAction($user_id, $article_id)
    {
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

            // Redirect to the URI of the resource.
            $response->headers->set('Location',
                $this->generateUrl('api_v1_get_user_article', [
                    'user_id' => $article->getUser()->getId(),
                    'article_id' => $article->getId(),
                ], /* absolute path = */true)
            );
            $response->setContent(json_encode([
                    'article' => ['id' => $article->getId() ]
                ]));
            return $response;
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
