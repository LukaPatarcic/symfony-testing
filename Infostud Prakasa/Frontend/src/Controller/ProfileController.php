<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use App\Service\ApiService;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function editAction(ApiService $service, Session $session, Request $request)
    {
        $key = $session->get('key');
        $userId = $session->get('id');
        if(!$key or !$userId) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ProfileFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if($form->isValid()) {
                $id = $form->get('id')->getData();
                try {
                    $client = $service->getClient();
                    $response = $client->put('/profile/'.$id,[
                        'headers' => [
                            'X-AUTH-TOKEN' => $key
                        ],
                        'body' => json_encode($form->getData())
                    ]);

                    return $this->render('profile/index.html.twig', [
                        'controller_name' => 'ProfileController',
                    ]);

                } catch (ClientException $exception) {
                    return $this->createNotFoundException();
                }
            }
        }

        try {
            $client = $service->getClient();
            $response = $client->get('/profile/get-for-user/'.$userId,[
                'headers' => [
                    'X-AUTH-TOKEN' => $key
                ]
            ]);

            return $this->render('profile/index.html.twig', [
                'controller_name' => 'ProfileController',
            ]);
        } catch (ClientException $exception) {
            return $this->createNotFoundException();
        }
    }
}
