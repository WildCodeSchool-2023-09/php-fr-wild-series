<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProgramFixtures extends Fixture
{

    public const PROGRAMS = [
        ['title' => 'Game of Thrones', 'poster' => 'got.jpeg'],
        ['title' => 'House of Dragons', 'poster' => 'hod.jpeg'],
        ['title' => 'Jujutsu Kaisen', 'poster' => 'jujutsu.jpeg'],
        ['title' => 'Stranger Things', 'poster' => 'stranger-things.png'],
    ];

    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    public function load(ObjectManager $manager): void
    {
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
            $manager->persist($program);
        }

        $manager->flush();
    }
}
