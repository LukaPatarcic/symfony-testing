<?php

namespace App\Controller;

use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $data = [
            'name' => 'Luka',
            'surname' => 'Patarcic',
        ];
        return $this->render('main/index.html.twig',[
            'data' => $data,
        ]);

    }


    /**
     * @Route("/form")
     */
    public function form(Request $request)
    {
        $form = $this->createForm(UserFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            dd($data);
        }

        return $this->render('main/form.html.twig',[
            'form' => $form->createView(),
        ]);

    }


}