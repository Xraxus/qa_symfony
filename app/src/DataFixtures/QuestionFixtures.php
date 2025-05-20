<?php

/**
 * Question fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Question;

/**
 * Class QuestionFixtures.
 */
class QuestionFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * This method is called by the base class to create and persist
     * 10 fake questions using Faker library.
     */
    protected function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $question = new Question();

            // Set a fake sentence as the question title
            $question->setTitle($this->faker->sentence);

            // Set a longer fake text as the content of the question
            $question->setContent($this->faker->paragraphs(3, true));

            // Set a random comment (new field)
            $question->setComment($this->faker->paragraph);

            // Set a random creation date within last 100 days
            $createdAt = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            );
            $question->setCreatedAt($createdAt);

            // Set a random updated date after createdAt and before now
            $updatedAt = \DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now')
            );
            $question->setUpdatedAt($updatedAt);

            // Persist the question entity
            $this->manager->persist($question);
        }

        // Flush all persisted objects to the database
        $this->manager->flush();
    }
}
