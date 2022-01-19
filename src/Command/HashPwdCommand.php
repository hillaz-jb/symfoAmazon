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

#[AsCommand(
    name: 'app:HashPwd',
    description: 'Add a short description for your command',
)]
class HashPwdCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');

        $allUser = $this->userRepository->findAll();

        foreach ($allUser as $user) {
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $user->getPassword(),
            ));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
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

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $output->writeln('Command finished !');
        return Command::SUCCESS;
    }
}
