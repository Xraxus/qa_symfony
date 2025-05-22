<?php

// src/DataFixtures/CommentFixture.php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $comment = new Comment();
        $comment->setContent('Thanks for the answer!');
        $comment->setEmail('guest@example.com');
        $comment->setNickname('GuestUser');
        $comment->setCreatedAt(new \DateTimeImmutable('-10 hours'));
        $comment->setQuestion($this->getReference('question-1'));
        $comment->setAuthor(null); // guest comment without user
        $manager->persist($comment);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [QuestionFixtures::class];
    }
}
