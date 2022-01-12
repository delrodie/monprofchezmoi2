<?php

namespace App\Utilities;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Security
{
    private $entityManager;
    private $passwordHasher;
    private $security;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, \Symfony\Component\Security\Core\Security $security, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Initialisation des utilisateurs par la creation du compte du super admin
     *
     * @return bool
     */
    public function initalisationUser(): bool
    {
        $user = new User();
        //$user->setUsername('delrodie');
        $user->setEmail('delrodieamoikon@gmail.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'monprofchezmoi2021'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Mise a jour de la table User
     *
     * @return bool
     */
    public function connexion(): bool
    {
        //$user = $this->security->getUser();
        $user = $this->userRepository->findOneBy(['email'=>$this->security->getUser()->getUserIdentifier()]); //dd($user);

        $nombre_connexion = $user->getConnexion();
        //$date = new \DateTime();
        $user->setConnexion($nombre_connexion+1);
        $user->setLastconnectedAt(new \DateTime());

        $this->entityManager->flush();

        return true;
    }
}
