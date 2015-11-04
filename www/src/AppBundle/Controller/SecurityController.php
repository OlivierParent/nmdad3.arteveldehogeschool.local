<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Security\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;


/**
 * Class SecurityController.
 *
 * @Route("/security")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     * @Method("GET")
     * @Template("Security/login.html.twig")
     *
     * @param Request $request
     *
     * @return array
     */
    public function loginAction(Request $request)
    {
        $user = new User();
        $form = $this->createLoginForm($user);

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $session = $request->getSession();
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        // Return array with variables for Twig.
        return [
            'error' => $error,
            'login_form' => $form->createView(),
        ];
    }

    /**
     * @param User $user
     * @return \Symfony\Component\Form\Form
     */
    public function createLoginForm(User $user)
    {
        $formType = new LoginType();
        $form = $this->createForm($formType, $user, [
            'action' => $this->generateUrl('security_login_check'),
        ]);

        return $form;
    }

}
