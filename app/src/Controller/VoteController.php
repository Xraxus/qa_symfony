<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/vote')]
final class VoteController extends AbstractController
{
    #[Route('/answer/{id}/{value}', name: 'vote_answer', methods: ['POST'])]
    public function voteAnswer(
        Answer $answer,
        int $value,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to vote.');
        }

        if (!in_array($value, [1, -1], true)) {
            // Invalid vote value, redirect without changes
            return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
        }

        // Check if user already voted on this answer
        foreach ($answer->getVotes() as $existingVote) {
            if ($existingVote->getUser() === $user) {
                $existingVote->setValue($value);
                $entityManager->flush();

                return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
            }
        }

        // Create new vote
        $vote = new Vote();
        $vote->setUser($user)
            ->setAnswer($answer)
            ->setValue($value);

        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }
}
