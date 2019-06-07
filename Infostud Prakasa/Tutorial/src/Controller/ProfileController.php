<?php


namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{

    /**
     * @Route(path="/", name="home")
     */
    public function showAction()
    {
        return $this->json(['content' => 'Hello World']);
    }

    /**
     * @Route(path="/profiles", methods={"POST"})
     */
    public function newAction(Request $request,EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(),true);
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->submit($data);

        if(!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true,true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
                dump($error);
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }
        $em->persist($profile);
        $em->flush();

        return $this->json($profile);
    }

    /**
    * @Route(path="/profiles/{id}", methods={"PUT"}, requirements={"id"="\d+"})
    */
    public function overwriteAction(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        $profile = $entityManager->getRepository(Profile::class)->find($id);
        $statusCode = Response::HTTP_Ok;
        if (!$profile) {
            $profile = new Profile();
            $statusCode = Response::HTTP_CREATED;
        }

        $data = json_decode($request->getContent(), 1);
        // To avoid crashes since name is required
        if (empty($data['name'])) {
            return $this->json(['error' => 'Name is a required field'], Response::HTTP_BAD_REQUEST);
        }

        $profile
            ->setName($data['name'] ?? null)
            ->setPib($data['pib'] ?? null)
            ->setMaticniBroj($data['maticniBroj'] ?? null)
            ->setAddress($data['address'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setPhoneNumber($data['phoneNumber'] ?? null)
            ->setDescription($data['description'] ?? null);

        $entityManager->persist($profile);
        $entityManager->flush();
        return $this->json($profile, $statusCode);
    }

    /**
     * @Route(path="/profiles", methods={"GET"})
     */
    public function collectionAction(ProfileRepository $repository, Request $request)
    {
        $params = $request->query->all();
//        dd($params);
        $results = $repository->search(
            $params,
            $params['page'] ?? 1,
            $params['pageSize'] ?? 10
        );

        return $this->json($results,Response::HTTP_OK,[],['groups' => 'get-profile']);
    }

    /**
     * @Route(path="/profiles/{id}", methods={"PATCH"})
     */
    public function patchAction($id, Request $request, ProfileRepository $repository, EntityManagerInterface $em)
    {
        $profile = $repository->findOneBy(['id' => $id]);
        if(!$profile)
            return $this->json(['error' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        $data = json_decode($request->getContent(),true);

        if (array_key_exists('name', $data) && empty($data['name'])) {
            return $this->json(['error' => 'Name can\'t be empty'], Response::HTTP_BAD_REQUEST);
        }

        $fieldsToCheck = ['pib','maticniBroj', 'address', 'phoneNumber', 'description', 'email','name'];

        foreach ($fieldsToCheck as $field) {
            if (array_key_exists($field, $data)) {
                $profile->{'set'.ucfirst($field)}($data[$field]);
            }
        }
        $em->flush();
        return $this->json($profile, Response::HTTP_OK);
    }
    /**
     * @Route(path="/profiles/{id}", methods={"GET"})
     */
    public function showOneUserAction(Profile $profile, EntityManagerInterface $em)
    {
        $em->remove($profile);
        $em->flush();
        return $this->json(['success' => 'Delete profile with name '.$profile->getName()],Response::HTTP_OK);

    }
    /**
     * @Route(path="/profiles/{id}", methods={"DELETE"})
     */
    public function deleteAction(Profile $profile, EntityManagerInterface $em)
    {
        $em->remove($profile);
        $em->flush();
        return $this->json(['success' => 'Delete profile with name '.$profile->getName()],Response::HTTP_OK);

    }

    /**
     * @Route(path="/profiles/get-for-user/{id}")
     */
    public function getProfileForUserAction(int  $id,ProfileRepository $repository)
    {
        $profile = $repository->findOneBy(['user' => $id]);
        if(!$profile) {
            return $this->jsonResponse([],Response::HTTP_NOT_FOUND);
        }
        return $this->jsonResponse($profile,Response::HTTP_OK);
    }

    public function jsonResponse($data = [], $code = Response::HTTP_OK)
    {
        return $this->json($data,$code,[],['groups' => 'get-profile']);
    }
}