<?php

namespace App\Controller;

use App\Form\FormType;
use App\Form\LoginFormType;
use App\Service\ApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/register", name="register")
     */
    public function index(Request $request)
    {

        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            try {
                $data = $form->getData();
                $client = new Client([
                    'headers' => [ 'Content-Type' => 'application/json' ]
                ]);

                $response = $client->post('http://localhost:8000/user',
                    ['body' => json_encode(
                        [
                            'email' => $data['email'],
                            'password' => $data['password']
                        ]
                    )]
                );

                if($response->getStatusCode() == Response::HTTP_CREATED){
                    unset($form);
                    $form = $this->createForm(FormType::class);
                    $this->addFlash('success','User has been created');
                }

            } catch (ClientException $exception) {
                $errors = json_decode($exception->getResponse()->getBody(),1);
                if(!empty($errors['errors']['email'])) {
                    $form->get('email')->addError(new FormError($errors['errors']['email']));
                }
            }
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/login",name="app_login")
     */
    public function loginAction(Request $request, Session $session)
    {
        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()) {
            try {
                $data = $form->getData();

                $client = new Client([
                    'headers' => [ 'Content-Type' => 'application/json' ]
                ]);

                $jsonData = json_encode(['username' => $data['email'], 'password' => $data['password']]);
                $response = $client->post('http://localhost:8000/user/login',[
                    'body' => $jsonData
                ]);



                if($response->getStatusCode() == Response::HTTP_OK){
                    $responseData = json_decode($response->getBody(),true);
                    $session->set('token',$responseData['token']);
                    $session->set('id',$responseData['id']);
                    return $this->redirectToRoute('app_profile');
                }

            } catch (ClientException $exception) {
                $errors = json_decode($exception->getResponse()->getBody(),1);
                if(!empty($errors['errors']['email'])) {
                    $form->get('email')->addError(new FormError($errors['errors']['email']));
                }
            }
        }

        return $this->render('home/login.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
