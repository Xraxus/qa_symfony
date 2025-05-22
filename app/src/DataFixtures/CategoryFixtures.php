<?php
// src/DataFixtures/CategoryFixture.php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cat = new Category();
        $cat->setName('Programming');
        $cat->setSlug('programming');
        $manager->persist($cat);
        $this->addReference('category-programming', $cat);

        $manager->flush();
    }
}
