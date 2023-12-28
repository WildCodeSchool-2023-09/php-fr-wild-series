<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        ['title' => 'Game of Thrones', 'poster' => 'got.jpeg', 'country' => 'USA', 'year' => 2011,
            'category' => 'Fantastique', 'reference' => 'program_1'],
        ['title' => 'House of Dragons', 'poster' => 'hod.jpeg', 'country' => 'USA', 'year' => 2022,
            'category' => 'Fantastique', 'reference' => 'program_2'],
        ['title' => 'Jujutsu Kaisen', 'poster' => 'jujutsu.jpeg', 'country' => 'Japon', 'year' => 2020,
            'category' => 'Animation', 'reference' => 'program_3'],
        ['title' => 'Stranger Things', 'poster' => 'stranger-things.png', 'country' => 'USA', 'year' => 2016,
            'category' => 'Science-Fiction', 'reference' => 'program_4'],
        ['title' => 'Naruto', 'poster' => 'naruto.jpeg', 'country' => 'Japon',
            'synopsis' => 'Un jeune ninja en quête de reconnaissance',
            'year' => 2002, 'category' => 'Animation', 'reference' => 'program_5'],
        ['title' => 'Breaking Bad', 'poster' => 'breaking-bad.jpeg', 'country' => 'USA', 'year' => 2010,
            'synopsis' => 'Un professeur devient dealer',
            'category' => 'Action', 'reference' => 'program_6'],
        ['title' => 'Rings Of Power', 'poster' => 'rings.jpeg', 'country' => 'USA', 'year' => 2021, 'synopsis' =>
            'Une elfe veut venger son peuple', 'category' => 'Action', 'reference' => 'program_7'],
        ['title' => 'The Last Of Us', 'poster' => 'lou.jpeg', 'country' => 'USA', 'year' => 2022,
            'synopsis' => 'Un voyage dans un monde apocalyptique', 'category' => 'Action',
            'reference' => 'program_8']
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
            $this->addReference($programName['reference'], $program);
            $manager->persist($program);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
