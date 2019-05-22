<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{

    /**
     * @Route(path="/", name="home")
     */
    public function showAction()
    {
        return $this->json(['content' => 'Hello World']);
    }

    /**
     * @Route(path="/calculate", methods={"POST"}, name="calc")
     */
    public function calculateAction(Request $request)
    {
        $data = json_decode($request->getContent(),1);
        $data['number1'] = $data['number1'] ?? 0;
        $data['number2'] = $data['number2'] ?? 0;
        $response = [
            'addition' => $data['number1'] + $data['number2'],
            'subtraction' => $data['number1'] - $data['number2']
        ];
        return $this->json($response);
    }

    /**
     * @Route(path="user/add", methods={"POST"})
     */
    public function addUserAction(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(),1);

        $user = new User();
        $user->setEmail($data['email']);
        $password = $encoder->encodePassword($user,$data['password']);
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        return $this->json(['status' => 1]);

    }

    /**
     * @Route(path="/user/get/all", methods={"GET"})
     */
    public function getAllUsersAction(UserRepository $repository)
    {
        return $this->json($repository->findAll());
    }

    /**
     * @Route(path="/user/get/{id}", methods={"GET"})
     */
    public function getOneUserAction(int $id, UserRepository $repository)
    {
        $user = $repository->findOneBy(['id' => $id]);
        if(!$user)
            return $this->json(['error' => 'User does not exist']);
        return $this->json($user);
    }
}