<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->addReference("user_" . $i, $user);
        }

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
