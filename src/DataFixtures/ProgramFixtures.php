<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProgramFixtures extends Fixture
{
    public const PROGRAMS = [
        ['title' => 'Game of Thrones', 'poster' => 'got.jpeg', 'country' => 'USA', 'year' => 2011,
            'category' => 'Fantastique'],
        ['title' => 'House of Dragons', 'poster' => 'hod.jpeg', 'country' => 'USA', 'year' => 2022,
            'category' => 'Fantastique'],
        ['title' => 'Jujutsu Kaisen', 'poster' => 'jujutsu.jpeg', 'country' => 'Japon', 'year' => 2020,
            'category' => 'Animation'],
        ['title' => 'Stranger Things', 'poster' => 'stranger-things.png', 'country' => 'USA', 'year' => 2016,
            'category' => 'Science-Fiction'],
    ];
    public const NUM_PROGRAM = 300;
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // $uploadProgramDir = '/uploads/program';
        $uploadProgramDir = $this->parameterBag->get('upload_program_dir');
        if (!is_dir(__DIR__ . '/../../public' . $uploadProgramDir)) {
            mkdir(__DIR__ . '/../../public' . $uploadProgramDir, recursive : true);
        }
        foreach (self::PROGRAMS as $programName) {
            copy(
                __DIR__ . '/data/program/' .  $programName['poster'],
                __DIR__ . '/../../public' . $uploadProgramDir . '/' . $programName['poster']
            );
            $program = new Program();
            $program->setTitle($programName['title']);
            $program->setPoster($programName['poster']);
            $program->setSynopsis($faker->paragraphs(4, true));
            $program->setCountry($programName['country']);
            $program->setYear($programName['year']);
            $program->setCategory($this->getReference('category_' . $programName['category']));
            $manager->persist($program);
        }
        foreach (self::PROGRAMS as $programName) {
            for ($i = 0; $i < self::NUM_PROGRAM; $i++) {
                $program2 = new Program();
                $program2->setTitle('Program ' . $i);
                $program2->setSynopsis($faker->paragraphs(4, true));
                $program2->setCountry('Pays' . $i);
                $program2->setYear($i);
                $program2->setCategory($this->getReference('category_' . $programName['category']));
                $manager->persist($program2);
            }
        }

        $manager->flush();
    }
}
