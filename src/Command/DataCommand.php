<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:data',
    description: 'Add Data ',
)]
class DataCommand extends Command
{

    private EntityManagerInterface $entityManager;


    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        //$this->setName('app:data');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');
        $categories = [['name' => 'jeux',
            'description' => 'lorem'],
                        ['name' => 'vêtements',
                            'description' => 'lorem'],
                        ['name' => 'alimentation',
                            'description' => 'lorem']
                    ];
        $order = 0;
        $progressBar = new ProgressBar($output, count($categories));
        $progressBar->start();

        foreach ($categories as $category) {

            $cat = new Category();

            $cat->setName($category['name']);
            $cat->setDescription($category['description']);
            $cat->setCategoryOrder($order);
            $order += 1;
            $this->entityManager->persist($cat);
            $this->entityManager->flush();
            $progressBar->advance();
        }
        $progressBar->finish();
        $output->writeln('Command finished !');

        return Command::SUCCESS;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
