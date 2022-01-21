<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:HashPwd',
    description: 'Add a short description for your command',
)]
class HashPwdCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private SluggerInterface $slugger;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param SluggerInterface $slugger;
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->slugger = $slugger;

    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');

        $allUser = $this->userRepository->findAll();

        //HASH PASSWORDS
        foreach ($allUser as $user) {
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $user->getPassword(),
            ));
            //SLUGUSER (useless pour user mais peut servir pour d'autres entities)
            $slugUser = $this->slugger->slug($user->getFirstName() . '-' . $user->getLastName());
            $user->setSlugUser($slugUser);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        //CREATE AN ADMIN USER
        $admin = new User;
        $admin->setFirstName('Jean');
        $admin->setLastName('Dupont');
        $admin->setEmail('admin@admin.fr');
        $admin->setRegisteredAt(new \DateTime());
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->userPasswordHasher->hashPassword(
            $admin,
            'admin',
        ));
        $slugAdmin = $this->slugger->slug($admin->getFirstName() . '-' . $admin->getLastName());
        $admin->setSlugUser($slugAdmin);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $output->writeln('Command finished !');
        return Command::SUCCESS;
    }
}
