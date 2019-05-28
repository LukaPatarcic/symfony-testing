<?php


namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

}