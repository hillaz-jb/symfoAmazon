<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ArticleRepository $ar;
    private EntityManagerInterface $em;

    /**
     * @param ArticleRepository $ar
     * @param EntityManagerInterface $em
     */
    public function __construct(ArticleRepository $ar, EntityManagerInterface $em)
    {
        $this->ar = $ar;
        $this->em = $em;
    }


    #[Route('/product', name: 'product')]
    public function index(Request $request): Response
    {
        $articles = $this->ar->findAll();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categories = $form->get('categories')->getData();
            $articles = $this->ar->findWithRelations($categories);
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'articles' => $articles,
//            'articles' => $this->ar->findBy([], ['name' => 'ASC']),
            'article_list' => $this->ar->findAll(),
            'form' => $form->createView(),
        ]);
    }
}
