<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('user@test.com')
            ->setUsername('user')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $admin = new User();
        $admin
            ->setEmail('admin@test.com')
            ->setUsername('admin')
            ->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
