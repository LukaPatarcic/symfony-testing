<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\User;
use App\Form\TransactionFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/user/dashboard")
 */
class UserDashboardController extends AbstractController
{
    /**
     * @Route("/", name="user_dashboard")
     */
    public function index(Request $request)
    {
        $transaction = new Transaction();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entityManager = $this->getDoctrine ()->getManager ();
        $transaction->setUser ($user);


        $form = $this->createForm (TransactionFormType::class,$transaction);
        $form->handleRequest ($request);

        if($form->isSubmitted () and $form->isValid ()) {

            $type =$transaction->getTransactionType ()->getType ();


            if($type == 'outcome') {

                $amount = $transaction->getAmount ();
                $transaction->setAmount (-1 * abs ($amount));
            }
            $entityManager->persist ($transaction);
            $entityManager->flush ();
            $this->addFlash('success', 'Transaction successfull');

        }

        $userData =$entityManager->getRepository (User::class)->find ($user);


        return $this->render('user_dashboard/index.html.twig', [
            'form' => $form->createView (),
            'balance' => $userData->getBalance (),
        ]);
    }
    /**
     * @Route("/profile", name="user_dashboard_profile")
     */
    public function profile()
    {
        return $this->render('user_dashboard/profile.html.twig', []);
    }

    /**
     * @Route("/tables", name="user_dashboard_tables")
     */
    public function tables()
    {
        return $this->render('user_dashboard/tables.html.twig', []);
    }

    /**
     * @Route("/statistics", name="user_dashboard_statistics")
     */
    public function statistics()
    {
        return $this->render('user_dashboard/statistics.html.twig', []);
    }
}
