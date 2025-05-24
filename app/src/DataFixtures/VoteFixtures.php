<?php

namespace App\DataFixtures;

use App\Entity\Vote;
use App\Entity\User;
use App\Entity\Answer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VoteFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    protected function loadData(): void
    {
        // Tworzymy np. 30 głosów
        $this->createMany(30, 'vote', function (int $i) {
            $vote = new Vote();

            /** @var User $user */
            $user = $this->getRandomReference('user', User::class);

            /** @var Answer $answer */
            $answer = $this->getRandomReference('answer', Answer::class);

            $vote->setUser($user);
            $vote->setAnswer($answer);
            $vote->setValue($this->faker->randomElement([-1, 1]));

            return $vote;
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AnswerFixtures::class,
        ];
    }
}
