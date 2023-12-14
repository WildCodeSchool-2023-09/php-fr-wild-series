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
        ['title' => 'Game of Thrones', 'poster' => 'got.jpeg', 'country' => 'USA', 'year' => 2011],
        ['title' => 'House of Dragons', 'poster' => 'hod.jpeg', 'country' => 'USA', 'year' => 2022],
        ['title' => 'Jujutsu Kaisen', 'poster' => 'jujutsu.jpeg', 'country' => 'Japon', 'year' => 2020],
        ['title' => 'Stranger Things', 'poster' => 'stranger-things.png', 'country' => 'USA', 'year' => 2016],
    ];

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
            $manager->persist($program);
        }

        $manager->flush();
    }
}
