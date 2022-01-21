<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account', name: '')]
class AccountController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    /**
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }


    /*#[Route('/', name: 'account_index')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }*/

    #[Route('/edit/{id}', name: 'account_edit')]
    public function editCountry(Request $request, User $account): Response {
        $form = $this->createForm(AccountFormType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('account_show');
        }
        return $this->render('account/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/', name: 'account_show')]
    public function show(): Response
    {
        if ($this->getUser()) {
            return $this->render('account/show.html.twig', [
                'account' => $this->getUser(),
            ]);
        }
        return $this->redirectToRoute('home_index');

    }
}
