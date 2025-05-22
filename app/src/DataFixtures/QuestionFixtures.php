<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    protected function loadData(): void
    {
        // Fetch all users, categories, and tags from DB (or your own way to get references)
        $users = $this->manager->getRepository(User::class)->findAll();
        $categories = $this->manager->getRepository(Category::class)->findAll();
        $tags = $this->manager->getRepository(Tag::class)->findAll();

        for ($i = 0; $i < 20; ++$i) {
            $question = new Question();

            $question->setTitle($this->faker->sentence);
            $question->setContent($this->faker->paragraphs(3, true));

            // status: pick one randomly from allowed statuses, e.g. 'draft', 'published', 'closed'
            $statuses = ['draft', 'published', 'closed'];
            $question->setStatus($this->faker->randomElement($statuses));

            // image: maybe null or a fake image filename
            $question->setImage($this->faker->boolean(70) ? $this->faker->imageUrl(640, 480, 'technics') : null);

            // createdAt & updatedAt
            $createdAt = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );
            $question->setCreatedAt($createdAt);
            $updatedAt = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now')
            );
            $question->setUpdatedAt($updatedAt);

            // author (required) - random user
            if (count($users) > 0) {
                $question->setAuthor($this->faker->randomElement($users));
            }

            // category (required) - random category
            if (count($categories) > 0) {
                $question->setCategory($this->faker->randomElement($categories));
            }

            // add 0 to 3 random tags
            if (count($tags) > 0) {
                $tagsCount = $this->faker->numberBetween(0, 3);
                $randomTags = $this->faker->randomElements($tags, $tagsCount);
                foreach ($randomTags as $tag) {
                    $question->addTag($tag);
                }
            }

            // No bestAnswer set here

            $this->manager->persist($question);
        }

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        // Add fixtures that create users, categories, tags here
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            TagFixtures::class,
        ];
    }
}
