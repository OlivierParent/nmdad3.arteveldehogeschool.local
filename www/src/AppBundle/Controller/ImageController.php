<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Form\Image as ImageForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ImageController.
 *
 * @Route("/posts-images")
 */
class ImageController extends Controller
{
    /**
     * Lists all Image entities.
     *
     * @Route("/", name="images")
     * @Method("GET")
     * @Template("Image/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findAll();

//        dump($images); // Dump to the Symfony Development Toolbar.

        // Send variables to the view.
        return [
            'images' => $images,
        ];
    }

    /**
     * Creates a new Image entity.
     *
     * @Route("/", name="images_create")
     * @Method("POST")
     * @Template("Image/new.html.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $image = new Image();
        $image->setUser($this->getUser());
        $form = $this->createCreateForm($image);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $uploadDirectory = 'uploads';
            $file = $image->getFile();
            $fileName = sha1_file($file->getRealPath()).'.'.$file->guessExtension();
            $fileLocator = realpath($this->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web').DIRECTORY_SEPARATOR.$uploadDirectory;
            $file->move($fileLocator, $fileName);
            $image->setUri('/'.$uploadDirectory.'/'.$fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return [
            'image' => $image,
            'new_form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Image entity.
     *
     * @param Image $image The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Image $image)
    {
        $formType = new ImageForm\NewType();
        $form = $this->createForm($formType, $image, [
            'action' => $this->generateUrl('images_create'),
            'method' => Request::METHOD_POST,
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Image entity.
     *
     * @Route("/new", name="images_new")
     * @Method("GET")
     * @Template("Image/new.html.twig")
     */
    public function newAction()
    {
        $image = new Image();
        $newForm = $this->createCreateForm($image);

        return [
            'new_form' => $newForm->createView(),
        ];
    }
}
