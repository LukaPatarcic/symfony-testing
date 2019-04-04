<?php

namespace App\Controller;

use App\Form\FormTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class SomethingController extends AbstractController
{
    /**
     * @Route("/something/{min}/{max}", name="something",requirements={"min"="\d+","max"="\d+"})
     */
    public function index($min = 1,$max = 10)
    {
        $number = random_int ($min,$max);

        $form = new FormTestType();
        $form->

        return $this->render('something/index.html.twig', [
            'controller_name' => 'SomethingController',
            'num' => $number
        ]);
    }

}
