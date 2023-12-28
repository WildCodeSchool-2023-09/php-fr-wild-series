<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Comment;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (ProgramFixtures::PROGRAMS as $programName) {
            $program = $this->getReference($programName['reference']);

            for ($j = 1; $j <= 5; $j++) {
                $user = $this->getReference("user_$j");

                $comment = new Comment();
                $comment->setComment($faker->paragraph);
                $comment->setRate($faker->numberBetween(1, 5));
                $comment->setAuthor($user);
                $comment->setProgram($program);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
            UserFixtures::class
        ];
    }
}
