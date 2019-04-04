<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends Controller
{
    /**
     * @Route ("/article",name="article_list")
     * @Method ({"GET"})
     */
    public function index()
    {
        $articles = $this->getDoctrine ()->getRepository (Article::class)->findAll ();

        return $this->render ('article/index.html.twig',[
            'articles' => $articles
        ]);
    }
    /**
     * @Route ("/article/show/{id}", name="article_show")
     * @Method ({"GET"})
     */
    public function show($id) {
        $article =$this->getDoctrine ()->getRepository (Article::class)->find ($id);

        return $this->render ('article/show.html.twig',[
            'article' =>$article
        ]);
    }

    /**
     * @Route ("/article/save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine ()->getManager ();

        $article = new Article();
        $article->setTitle ('Article One');
        $article->setDescription ('Article Description');

        $entityManager->persist ($article);//saves to push to database
        $entityManager->flush ();//pushes to database

        return new Response('Saved an article with the id of '.$article->getId ());
        /*return $this->render ('article/index.html.twig',[

        ]);*/
    }

    /**
     * @Route ("/article/new",name="new_article")
     * @Method ({"GET"},{"POST"})
     */
    public function new(Request $request) {
        $article = new Article();

        $form = $this->createFormBuilder ($article)
            ->add ('title', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Article Name'
                ]
            ])
            ->add ('description',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Article Description'
                ]
            ])
            ->add('save',SubmitType::class, [
              'label' => 'Create',
              'attr' => [
                  'class' => 'btn btn-primary mt-3'
              ]
            ])
            ->getForm ();

        $form->handleRequest ($request);

        if($form->isSubmitted () && $form->isValid ()) {
            $article =$form->getData ();

            $entityManager = $this->getDoctrine ()->getManager ();
            $entityManager->persist ($article);
            $entityManager->flush ();

            return $this->redirectToRoute ('article_list');
        }

        return $this->render ('article/new.html.twig',[
            'form' => $form->createView ()
        ]);
    }

    /**
     * @Route ("/article/delete/{id}",name="article_delete")
     * @Method ({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine ()->getRepository (Article::class)->find ($id);
        $entityManager = $this->getDoctrine ()->getManager ();
        $entityManager->remove ($article);
        $entityManager->flush ();

        $response = new Response();
        $response->send ();
    }

    /**
     * @Route("/article/edit/{id}",name="article_edit")
     * @Method({"GET"},{"POST"})
     */
    public function edit(Request $request,$id) {

        $article = $this->getDoctrine ()->getRepository (Article::class)->find ($id);
        $form = $this->createFormBuilder ($article)
        ->add ('title',TextType::class,[
            'attr' => [
                'class' => 'form-control'
                ]
            ])
        ->add('description',TextareaType::class,[
            'attr' => [
                'class' => 'form-control'
                ]
            ])
        ->add('Edit',SubmitType::class,[
            'attr' => [
                'class' => 'btn btn-primary mt-3'
            ]
        ])
        ->getForm ();

        $form->handleRequest ($request);

        if($form->isSubmitted () && $form->isValid ()) {
            $entityManage = $this->getDoctrine ()->getManager ();
            $entityManage->persist ($form->getData ());
            $entityManage->flush ();

            return $this->redirectToRoute ('article_list');
        }

        return $this->render ('article/edit.html.twig',[
            'form' => $form->createView ()
        ]);
    }
}