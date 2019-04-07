<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash('success','Item added successfully');
            return $this->redirectToRoute('category');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/show/{id}", name="category_show")
     */
    public function show($id)
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findBy(['category' => $id]);
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        return $this->render('category/show.html.twig', [
            'items' => $items,
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit(Request $request,$id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success','Item edited successfully');
            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/delete/{id}", name="category_remove")
     */
    public function delete($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success','Item deleted successfully');
        return $this->redirectToRoute('category');
    }
}
