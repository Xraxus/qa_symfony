<?php

// src/Controller/QuestionController.php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'question_list')]
    public function list(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $queryBuilder = $questionRepository->createQueryBuilder('q')
            ->orderBy('q.createdAt', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder, /* zapytanie lub QueryBuilder */
            $request->query->getInt('page', 1), /* numer strony, domyślnie 1 */
            10 /* limit elementów na stronę */
        );

        return $this->render('question/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/question/{id}', name: 'question_show')]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }
}
