<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="user")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError ();
        $lastUsername = $utils->getLastUsername ();


        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }
}
