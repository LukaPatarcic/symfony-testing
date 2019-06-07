<?php


namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     * @Route(path="/user/login",methods={"POST"})
     */
    public function loginAction(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, UserRepository $repository)
    {
        $data = json_decode($request->getContent(),true);

        if(empty($data['username']) or empty($data['password'])) {
            return $this->getInvalidUsernameAndPasswordResponse();
        }

        $user = $repository->findOneBy(['email' => $data['username']]);
        if(!$user or !$encoder->isPasswordValid($user,$data['password'])){
            return $this->getInvalidUsernameAndPasswordResponse();
        }
        $token = hash('sha256', $user->getEmail().bin2hex(random_bytes(64)));
        $user->setApiKey($token);
        $user->setKeyExpirationTime(new \DateTime('+8 hours'));
        $manager->flush();
        return $this->json([
            'token' => $token,
            'id' => $user->getId()
        ], Response::HTTP_OK);
    }

    private function getInvalidUsernameAndPasswordResponse()
    {
        return $this->json(['error' => 'Invalid username or password'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route(path="user", methods={"POST"})
     */
    public function newAction(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(),1);

        $user = new User();
        $form = $this->createForm(UserType::class);
        $form->submit($data);

        if(!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true,true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $user->setEmail($data['email']);
        $password = $encoder->encodePassword($user,$data['password']);
        $user->setPassword($password);
        $user->setRoles(['ROLE_API_USER']);

        $em->persist($user);
        $em->flush();

        return $this->json(['status' => 1],Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/user", methods={"GET"})
     */
    public function getAllUsersAction(UserRepository $repository)
    {
        $users = $repository->findAll();
        if(!$users)
            return $this->json($users,204);
        return $this->json($users,200);
    }

    /**
     * @Route(path="/user/{id}", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getOneUserAction(int $id, UserRepository $repository)
    {
        $user = $repository->findOneBy(['id' => $id]);
        if(!$user)
            return $this->json(['error' => 'User does not exist'],204);
        return $this->json($user);
    }

    /**
     *
     * @Route(path="/user/{id}", methods={"DELETE"})
     */
    public function deleteUserAction(int $id, UserRepository $repository, EntityManagerInterface $em)
    {
        $user = $repository->findOneBy(['id' => $id]);
        if(!$user)
            return $this->json(['error' => 'User does not exist']);
        $email = $user->getEmail();
        $em->remove($user);
        $em->flush();
        return $this->json(['success' => 'User '.$email.' deleted']);
    }

    /**
     * @Route(path="/user/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function editUserAction(int $id, UserRepository $repository, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $repository->findOneBy(['id' => $id]);
        if(!$user)
            return $this->json(['error' => 'User does not exist']);
        $data = json_decode($request->getContent(),1);
        $user->setEmail($data['email'] ? $data['email'] : $user->getEmail());
        $password = $encoder->encodePassword($user,$data['password'] ?? $user->getPassword());
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();

        return $this->json(['user' => $user],200);
    }

    /**
     * @Route(path="/user/{id}", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function patchUserAction(int $id, UserRepository $repository, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $repository->findOneBy(['id' => $id]);
        if(!$user)
            return $this->json(['error' => 'User does not exist'],204);
        $data = json_decode($request->getContent(),1);
        $availableKeys = ['email','password'];
        foreach ($data as $key => $value) {
            if(!in_array($key,$availableKeys)) {
                return $this->json(['error' => sprintf('The filed %s is not valid',$key)], 400);
            }
            if(empty($data[$key])) {
                return $this->json(['error' => sprintf('The filed %s should not be empty',$key)], 400);
            }

            if($key === 'password') {
                $password = $encoder->encodePassword($user,$data['password'] ?? $user->getPassword());
                $user->setPassword($password);
                continue;
            }
            $user->{'set'.ucfirst($key)}($value);
        }

        $em->persist($user);
        $em->flush();

        return $this->json($user,200);
    }

    private function canUserChangeProfile(Profile $profile)
    {
        $user = $this->getUser();

    }
}