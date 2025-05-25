<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'category', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->unique()->word());

            return $category;
        });
    }
}
