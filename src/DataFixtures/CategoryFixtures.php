<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        "Action",
        "Animation",
        "Fantastique",
        "Science-Fiction"
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $categoryName, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
