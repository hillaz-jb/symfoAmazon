<?php

namespace App\Command;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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
    private CategoryRepository $categoryRepository;


    public function __construct(
        EntityManagerInterface $entityManager, CategoryRepository $categoryRepository
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    protected function configure(): void
    {
        //$this->setName('app:data');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');

        $categoriesToDelete = $this->categoryRepository->findAll();

        foreach ($categoriesToDelete as $childCategory) {
            if ($childCategory->getParent() !== null) {
                $this->entityManager->remove($childCategory);
            }
        }
        foreach ($categoriesToDelete as $parentCategory) {
            $this->entityManager->remove($parentCategory);
        }
        $this->entityManager->flush();

        $categories = [
            ['name' => 'jeux',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'parent' => null,
            ],
            ['name' => 'vêtements',
                'description' => 'Odio ut sem nulla pharetra diam sit. Nec ullamcorper sit amet risus nullam eget felis eget nunc.',
                'parent' => null,
            ],
            ['name' => 'alimentation',
                'description' => 'Egestas sed sed risus pretium quam. Ultrices mi tempus imperdiet nulla.',
                'parent' => null,
            ],
            ['name' => 'jeux de société',
                'description' => 'Egestas sed sed risus pretium quam. Ultrices mi tempus imperdiet nulla.',
                'parent' => 'jeux',
            ],
            ['name' => 'jouets',
                'description' => 'Egestas sed sed risus pretium quam. Ultrices mi tempus imperdiet nulla.',
                'parent' => 'jeux',
            ],
            ['name' => 'vêtements hommes',
                'description' => 'Odio ut sem nulla pharetra diam sit. Nec ullamcorper sit amet risus nullam eget felis eget nunc.',
                'parent' => 'vêtements',
            ],
            ['name' => 'vêtements femmes',
                'description' => 'Odio ut sem nulla pharetra diam sit. Nec ullamcorper sit amet risus nullam eget felis eget nunc.',
                'parent' => 'vêtements',
            ],
            ['name' => 'vêtements enfants',
                'description' => 'Odio ut sem nulla pharetra diam sit. Nec ullamcorper sit amet risus nullam eget felis eget nunc.',
                'parent' => 'vêtements',
            ],
            ['name' => 'gateaux',
                'description' => 'Egestas sed sed risus pretium quam. Ultrices mi tempus imperdiet nulla.',
                'parent' => 'alimentation',
            ],
            ['name' => 'bonbons',
                'description' => 'Egestas sed sed risus pretium quam. Ultrices mi tempus imperdiet nulla.',
                'parent' => 'alimentation',
            ],
        ];

        $order = 0;
        $progressBar = new ProgressBar($output, count($categories));
        $progressBar->start();

        foreach ($categories as $category) {
            $cat = new Category();
            $cat->setName($category['name']);
            $cat->setDescription($category['description']);
            $cat->setParent($this->categoryRepository->findOneBy(['name' => $category['parent']]));
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
