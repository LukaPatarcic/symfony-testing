<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item")
     */
    public function index()
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * @Route("/item/add",name="item_add")
     */
    public function add(Request $request,FileUploader $fileUploader)
    {

        $item = new Item;
        $form = $this->createForm(ItemType::class,$item);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {

            $file = $request->files->get('item')['image'];

            $fileName = $fileUploader->upload($file);
            $item->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();
            return $this->redirectToRoute('item');
        }
        return $this->render('item/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
