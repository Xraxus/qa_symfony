<?php
// src/DataFixtures/AnswerFixture.php
namespace App\DataFixtures;

use App\Entity\Answer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswerFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $answer = new Answer();
        $answer->setContent('You can start by reading the Symfony docs and using the Maker bundle.');
        $answer->setCreatedAt(new \DateTimeImmutable('-12 hours'));
        $answer->setIsBest(true);
        $answer->setQuestion($this->getReference('question-1'));
        $answer->setAuthor($this->getReference('user-admin'));
        $manager->persist($answer);

        // Link the best answer to the question:
        $question = $this->getReference('question-1');
        $question->setBestAnswer($answer);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [QuestionFixtures::class, UserFixtures::class];
    }
}
