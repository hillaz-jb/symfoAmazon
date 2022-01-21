<?php

namespace App\Command;


use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'addImg',
    description: 'Add img for article since there category, and fuck for my english',
)]
class AddImgCommand extends Command
{

    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ArticleRepository $articleRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        {

            $arrayLien = [
                'Jeux' => 'build/images/jeux.jpg',
                'vetements' => 'build/images/vetements.jpg',
                'alimentation' => 'build/images/alimentation.jpg',
                'Jeux Video' => 'build/images/jeuxVideo.jpg',
                'Jouet pour adulte' => 'build/images/jouetAdulte.jpg',
                'vetement homme' => 'build/images/vetementHomme.jpg',
                'vetement femme' => 'build/images/vetementFemme.jpg',
                'vetement enfant' => 'build/images/vetementEnfant.jpg',
                'gateaux' => 'build/images/gateau.jpg',
                'bonbon' => 'build/images/bonbon.jpg',
                'epicerie' => 'build/images/epice.jpg',
                'jeux de societe' => 'build/images/jeuxSociete.jpg',
            ];
        }
        $output->writeln('Command starting...');
        $allArticle = $this->articleRepository->findAll();
        foreach ($allArticle as $article) {
            foreach ($arrayLien as $key => $image) {
                if (($article->getCategories())[0]->getName() == $key) {
                    $article->setImg($image);
                    $this->entityManager->persist($article);
                }
            }
        }
        $this->entityManager->flush();
        $output->writeln('Command finished !');
        return Command::SUCCESS;
    }
}
