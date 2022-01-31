<?php

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private EntityManagerInterface $em;
    private ArticleRepository $articleRepository;

    /**
     * @param EntityManagerInterface $em
     * @param ArticleRepository $articleRepository
     */
    public function __construct(EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }


    #[Route('/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/new_article', name: 'article_new')]
    public function createArticle(Request $request, SluggerInterface $slugger): Response {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('img')->getData();
            /**@var UploadedFile $file*/
            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //dump($originalFileName);
                $safeFileName = $slugger->slug($originalFileName);
                $newName = $safeFileName . uniqid() . '.' . $file->guessExtension();

                try{
                    $file->move($this->getParameter('upload_dir'), $newName);
                    $article->setImg($newName);

                } catch (FileException $fileException) {
                    dump($fileException->getMessage());
                }

            }

            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('admin_index');
        }
        return $this->render('admin/newArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
