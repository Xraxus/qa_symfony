<?php

// src/Controller/QuestionController.php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'question_list')]
    public function list(QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAll();

        return $this->render('question/list.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Question $question): Response
    {
        // $question jest już wczytany z bazy
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }
}
