<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 250; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->realText(15));
            $episode->setNumber(($i / 25 + 1));
            $episode->setSynopsis($faker->realText(250));
            $episode->setSeason($this->getReference('season_' . ($i % 40 + 1)));
            $manager->persist($episode);
            $manager->flush();
        }
    }
    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
