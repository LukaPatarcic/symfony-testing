<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends Controller
{
    /**
     * @Route("/item", name="item_list")
     */
    public function index()
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * @Route("/item/new",name="item_new")
     */
    public function new(Request $request)
    {
        $item = new Item();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $form = $this->createFormBuilder($item)
            ->add('title',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Item Title'
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Item Description'
                ]
            ])
            ->add('article',EntityType::class,[
                'attr' => [
                  'class' => 'form-control col-sm-6'
                ],
                'class' => Item::class,
                'choices' => [
                    $item->getTitle()
                ]
            ])
            ->add('add',SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-block btn-primary mt-3'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $item = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_list');
        }
        return $this->render('item/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
