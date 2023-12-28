<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $programReferences = [];

        for ($i = 1; $i <= 8; $i++) {
            $programReferences[] = $this->getReference('program_' . $i);
        }

        for ($i = 0; $i < 20; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());

            shuffle($programReferences);

            for ($j = 0; $j < 3; $j++) {
                $actor->addProgram($programReferences[$j]);
            }

            $manager->persist($actor);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
