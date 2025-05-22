<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;

class VoteController extends AbstractController
{
    #[Route('/vote/answer/{id}/{value}', name: 'vote_answer', methods: ['POST'])]
    public function voteAnswer(Answer $answer, int $value, EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Check for existing vote
        foreach ($answer->getVotes() as $existingVote) {
            if ($existingVote->getUser() === $user) {
                $existingVote->setValue($value);
                $em->flush();
                return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
            }
        }

        // New vote
        $vote = new Vote();
        $vote->setUser($user)
            ->setAnswer($answer)
            ->setValue($value);

        $em->persist($vote);
        $em->flush();

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }

    #[Route('/vote/comment/{id}/{value}', name: 'vote_comment', methods: ['POST'])]
    public function voteComment(Comment $comment, int $value, EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Check for existing vote
        foreach ($comment->getVotes() as $existingVote) {
            if ($existingVote->getUser() === $user) {
                $existingVote->setValue($value);
                $em->flush();
                return $this->redirectToRoute('question_show', ['id' => $comment->getAnswer()->getQuestion()->getId()]);
            }
        }

        // New vote
        $vote = new Vote();
        $vote->setUser($user)
            ->setComment($comment)
            ->setValue($value);

        $em->persist($vote);
        $em->flush();

        return $this->redirectToRoute('question_show', ['id' => $comment->getAnswer()->getQuestion()->getId()]);
    }
}
