<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'tag', function (int $i) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->word());

            return $tag;
        });
    }
}
