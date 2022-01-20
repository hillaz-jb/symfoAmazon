<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $isAdmin = false;

        if ($this->getUser()) {
            if (in_array('ROLE_ADMIN', $user->getRoles())){
                $isAdmin = true;
            }
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'is_admin' => $isAdmin,
        ]);
    }
}
