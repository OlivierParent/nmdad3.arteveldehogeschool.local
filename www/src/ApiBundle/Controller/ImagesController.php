<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\ImageType;
use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ImagesController.
 */
class ImagesController extends Controller
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
    public function optionsImagesAction()
    {
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');

        return $response;
    }

    /**
     * Returns all images.
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
     *     description = "Order by Image id or Image title."
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
    public function getImagesAction(ParamFetcher $paramFetcher, $user_id)
    {
        # HTTP method: GET
        # Host/port  : http://www.nmdad3.arteveldehogeschool.local
        #
        # Path       : /app_dev.php/api/v1/users/1/images.json
        # Path       : /app_dev.php/api/v1/users/1/images.xml
        # Path       : /app_dev.php/api/v1/users/1/images.xml?sort=title&amp;order=desc

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

        $images = $posts
            ->filter(
                function ($post) {
                    return $post instanceof Image;
                }
            )->getValues();

        return $images;
    }

    /**
     * Returns an image.
     *
     * @param $user_id
     * @param $image_id
     *
     * @return object
     *
     * @FOSRest\Get(
     *     requirements = {
     *         "image_id": "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK         : "OK",
     *         Response::HTTP_NO_CONTENT : "No Content",
     *         Response::HTTP_NOT_FOUND  : "Not Found"
     *     }
     * )
     */
    public function getImageAction($user_id, $image_id)
    {
        # HTTP method: GET
        # Host/port  : http://www.nmdad3.arteveldehogeschool.local
        #
        # Path       : /app_dev.php/api/v1/users/1/images/1.json

        $em = $this->getDoctrine()->getManager();

        $image = $em
            ->getRepository('AppBundle:Image')
            ->find($image_id);

        if (!$image instanceof Image) {
            throw new NotFoundHttpException('Not found');
        }

        if ($image->getUser()->getId() === (int) $user_id) {
            return $image;
        }
    }

    /**
     * Post a new image.
     *
     * { "image": { "title": "Lorem" } }
     *
     * @param Request $request
     * @param $user_id
     *
     * @return View|Response
     *
     * @FOSRest\View()
     * @FOSRest\Post(
     *     "/users/{user_id}/images/",
     *     requirements = {
     *         "user_id" : "\d+"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\ImageType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postImageAction(Request $request, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository('AppBundle:User')
            ->find($user_id);
        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $image = new Image();
        $image->setUser($user);

        $logger = $this->get('logger');
        $logger->info($request);

        return $this->processImageForm($request, $image);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param $image_id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @FOSRest\View()
     * @FOSRest\Post(
     *     "/users/{user_id}/images/{image_id}/file/",
     *     requirements = {
     *         "user_id"   : "\d+"
     *     }
     * )
     */
    public function postImageFileAction(Request $request, $user_id, $image_id)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($image_id);

        $data = $_FILES['imageFile'];
        $file = new UploadedFile($data['tmp_name'], $data['name'], $data['type'], $data['size'], $data['error']);

        $uploadDirectory = 'uploads';
        $fileName = sha1_file($file->getRealPath()).'.'.$file->guessExtension();
        $fileLocator = realpath($this->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web').DIRECTORY_SEPARATOR.$uploadDirectory;
        $file->move($fileLocator, $fileName);
        $image->setUri($request->getScheme().'://'.$request->getHttpHost().'/'.$uploadDirectory.'/'.$fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        $response = new Response();

        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update an image.
     *
     * @param Request $request
     * @param $user_id
     * @param $image_id
     *
     * @return Response
     *
     * @FOSRest\View()
     * @FOSRest\Put(
     *     requirements = {
     *         "user_id" : "\d+",
     *         "image_id": "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     input = AppBundle\Form\ImageType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putImageAction(Request $request, $user_id, $image_id)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em
            ->getRepository('AppBundle:Image')
            ->find($image_id);

        if (!$image instanceof Image) {
            throw new NotFoundHttpException();
        }

        if ($image->getUser()->getId() === (int) $user_id) {
            return $this->processImageForm($request, $image);
        }
    }

    /**
     * Delete an image.
     *
     * @param $user_id
     * @param $image_id
     *
     * @throws NotFoundHttpException
     * @FOSRest\View(statusCode = 204)
     * @FOSRest\Delete(
     *     requirements = {
     *         "user_id" : "\d+",
     *         "image_id" : "\d+",
     *         "_format" : "json|xml"
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
    public function deleteImageAction($user_id, $image_id)
    {
        $em = $this->getDoctrine()->getManager();

        $image = $em
            ->getRepository('AppBundle:Image')
            ->find($image_id);

        if (!$image instanceof Image) {
            throw new NotFoundHttpException();
        }

        if ($image->getUser()->getId() === (int) $user_id) {
            $em->remove($image);
            $em->flush();
        }
    }

    // Convenience methods
    // -------------------

    /**
     * Process ImageType Form.
     *
     * @param Request $request
     * @param Image   $image
     *
     * @return View|Response
     */
    private function processImageForm(Request $request, Image $image)
    {
        $form = $this->createForm(new ImageType(), $image, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($image->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;

            $em = $this->getDoctrine()->getManager();
            $em->persist($image); // Manage entity Image for persistence.
            $em->flush();           // Persist to database.

            $response = new Response();
            $response->setStatusCode($statusCode);

            // Redirect to the URI of the resource.
            $response->headers->set('Location',
                $this->generateUrl('api_v1_get_user_image', [
                    'user_id' => $image->getUser()->getId(),
                    'image_id' => $image->getId(),
                ], /* absolute path = */true)
            );

            $response->setContent(json_encode([
                'image' => ['id' => $image->getId()],
            ]));

            return $response;
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
