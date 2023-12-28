<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 40; $i++) {
            $season = new Season();
            $season->setNumber(($i / 5 + 1));
            $season->setYear($faker->year());
            $season->setDescription($faker->paragraphs(3, true));
            $this->setReference('season_' . ($i % 40 + 1), $season);
            $season->setProgram($this->getReference('program_' . ($i % 8 + 1)));
            $manager->persist($season);
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
