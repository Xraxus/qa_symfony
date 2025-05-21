<?php

// src/Controller/QuestionController.php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * Displays paginated list of questions.
     *
     * @param QuestionRepository $questionRepository Repository for question entities
     * @param PaginatorInterface $paginator          KNP paginator for paginating results
     * @param int                $page               Page number from query string (default: 1)
     *
     * @return Response HTTP response with paginated question list
     */
    #[Route('/question', name: 'question_list', methods: ['GET'])]
    public function list(
        QuestionRepository $questionRepository,
        PaginatorInterface $paginator,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        $pagination = $paginator->paginate(
            $questionRepository->queryAll(),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['question.id', 'question.createdAt', 'question.updatedAt', 'question.title'],
                'defaultSortFieldName' => 'question.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render('question/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Displays a single question.
     *
     * @param Question $question Question entity
     *
     * @return Response HTTP response with question detail view
     */
    #[Route('/question/{id}', name: 'question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }
}
