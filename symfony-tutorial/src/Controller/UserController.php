<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user")
     */
    public function index()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
